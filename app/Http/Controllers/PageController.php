<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\OgImageGenerator;
use App\Support\PortfolioDataStore;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    private const SKILL_CATEGORY_META = [
        'networking' => [
            'label' => 'Networking',
            'description' => 'Primary focus for network support, troubleshooting, routing, switching, and practical Packet Tracer implementation.',
        ],
        'webDevelopment' => [
            'label' => 'Web Development',
            'description' => 'Secondary supporting skills for web-based solutions, Laravel applications, and content-driven development.',
        ],
        'androidDevelopment' => [
            'label' => 'Android Development',
            'description' => 'Supporting mobile development capabilities with Java, Android Studio, and Firebase.',
        ],
        'productivityTools' => [
            'label' => 'Productivity Tools',
            'description' => 'Day-to-day tools for documentation, reporting, structured work, and AI-assisted project building.',
        ],
        'professionalStrengths' => [
            'label' => 'Professional Strengths',
            'description' => 'Transferable strengths shaped by military service, instruction, coordination, and disciplined execution.',
        ],
        'frontend' => [
            'label' => 'Frontend',
            'description' => 'Frontend skills carried forward from earlier project work.',
        ],
        'backend' => [
            'label' => 'Backend',
            'description' => 'Backend and server-side development skills from existing project work.',
        ],
        'tools' => [
            'label' => 'Tools',
            'description' => 'General tools and supporting technology skills.',
        ],
    ];

    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
        private readonly OgImageGenerator $ogImageGenerator,
    ) {
    }

    public function portfolio(): Response
    {
        $portfolioData = $this->portfolioDataStore->publicWithDefaults();

        return $this->htmlResponse(
            'portfolio',
            $portfolioData,
            [
                'authenticated' => false,
                'user' => null,
            ]
        );
    }

    public function admin(): Response
    {
        $admin = $this->adminUser();

        return $this->htmlResponse(
            'admin',
            $admin ? $this->portfolioDataStore->full() : [],
            [
                'authenticated' => $admin !== null,
                'user' => $admin ? [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ] : null,
            ]
        );
    }

    public function ogImage(): BinaryFileResponse
    {
        $path = $this->ogImageGenerator->ensureGenerated();

        if ($path === null) {
            $path = $this->ogImageGenerator->sourcePath();
        }

        abort_unless(File::exists($path), 404);

        return response()->file($path, [
            'Content-Type' => (string) (File::mimeType($path) ?: 'image/jpeg'),
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function sitemap(): Response
    {
        $portfolioData = $this->portfolioDataStore->publicWithDefaults();
        $canonicalUrl = $this->canonicalUrl(Arr::get($portfolioData, 'contact.portfolioUrl'));

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
            '  <url>',
            '    <loc>'.e($canonicalUrl).'</loc>',
            '  </url>',
            '</urlset>',
        ]);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function robots(): Response
    {
        $portfolioData = $this->portfolioDataStore->publicWithDefaults();
        $canonicalUrl = $this->canonicalUrl(Arr::get($portfolioData, 'contact.portfolioUrl'));
        $sitemapUrl = $this->joinUrl($canonicalUrl, 'sitemap.xml');

        $robots = implode(PHP_EOL, [
            'User-agent: *',
            'Allow: /',
            'Sitemap: '.$sitemapUrl,
            '',
        ]);

        return response($robots, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function htmlResponse(string $viewName, array $portfolioData, array $adminContext): Response
    {
        $csrfToken = csrf_token();
        $pageMeta = $viewName === 'portfolio'
            ? $this->portfolioPageMeta($portfolioData)
            : [];

        if ($viewName === 'portfolio') {
            $html = $this->renderPhpTemplate(
                resource_path('views/portfolio.blade.php'),
                $this->portfolioViewData($portfolioData, $pageMeta)
            );
        } else {
            $html = File::get(resource_path("views/{$viewName}.blade.php"));
        }

        $html = $this->applyAssetVersioning($html);

        $script = sprintf(
            '<meta name="csrf-token" content="%s">%s<script>window.__PORTFOLIO_DATA__=%s;window.__ADMIN_CONTEXT__=%s;window.__CSRF_TOKEN__=%s;window.__PORTFOLIO_META__=%s;</script>%s</head>',
            e($csrfToken),
            PHP_EOL,
            json_encode($portfolioData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($adminContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($csrfToken, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($pageMeta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            PHP_EOL
        );

        $html = str_replace('</head>', $script, $html);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    private function applyAssetVersioning(string $html): string
    {
        foreach (['style.css', 'admin.css', 'data.js', 'renderer.js', 'script.js', 'admin.js'] as $asset) {
            $assetVersion = $this->assetVersion($asset);
            $pattern = sprintf('/%s\\?v=[^"\']*/', preg_quote($asset, '/'));
            $replacement = sprintf('%s?v=%s', $asset, $assetVersion);
            $html = preg_replace($pattern, $replacement, $html) ?? $html;
        }

        return $html;
    }

    private function assetVersion(string $asset): string
    {
        return $this->fileVersion(public_path($asset));
    }

    private function fileVersion(string $path): string
    {
        if (! File::exists($path)) {
            return '1';
        }

        $hash = sha1_file($path);

        if ($hash === false) {
            return (string) File::lastModified($path);
        }

        return substr($hash, 0, 10);
    }

    private function portfolioPageMeta(array $portfolioData): array
    {
        $meta = $portfolioData['meta'] ?? [];
        $contact = $portfolioData['contact'] ?? [];
        $title = $this->cleanMetaText($meta['siteTitle'] ?? $meta['name'] ?? 'Portfolio');
        $description = $this->cleanMetaText($meta['siteDesc'] ?? '');
        $keywords = $this->cleanMetaText($meta['siteKeywords'] ?? '');
        $author = $this->cleanMetaText($meta['name'] ?? $title);
        $siteName = $this->cleanMetaText($meta['brandText'] ?? $meta['name'] ?? $title);
        $canonicalUrl = $this->canonicalUrl($contact['portfolioUrl'] ?? null);
        $generatedOgImage = $this->ogImageGenerator->ensureGenerated();
        $ogImagePath = $generatedOgImage ?? $this->ogImageGenerator->sourcePath();
        $ogImageUrl = url($generatedOgImage ? '/og-image.jpg' : '/profile.png');
        $ogImageAlt = $this->cleanMetaText(($meta['name'] ?? $title).' profile preview image');

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'author' => $author,
            'siteName' => $siteName,
            'canonicalUrl' => $canonicalUrl,
            'ogImageUrl' => $ogImageUrl.'?v='.$this->fileVersion($ogImagePath),
            'ogImageAlt' => $ogImageAlt,
            'ogImageWidth' => $this->ogImageGenerator->width(),
            'ogImageHeight' => $this->ogImageGenerator->height(),
        ];
    }

    private function portfolioViewData(array $portfolioData, array $pageMeta): array
    {
        return [
            'portfolio' => $portfolioData,
            'pageMeta' => $pageMeta,
            'skillCategoryMeta' => self::SKILL_CATEGORY_META,
            'structuredDataJson' => $this->portfolioStructuredDataJson($portfolioData, $pageMeta),
        ];
    }

    private function canonicalUrl(mixed $portfolioUrl): string
    {
        if (is_string($portfolioUrl)) {
            $candidate = trim($portfolioUrl);

            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_URL)) {
                return str_ends_with($candidate, '/') ? $candidate : $candidate.'/';
            }
        }

        $fallback = url('/');

        return str_ends_with($fallback, '/') ? $fallback : $fallback.'/';
    }

    private function cleanMetaText(mixed $value): string
    {
        if (! is_scalar($value)) {
            return '';
        }

        $text = trim(strip_tags((string) $value));

        return preg_replace('/\s+/u', ' ', $text) ?? $text;
    }

    private function portfolioStructuredDataJson(array $portfolioData, array $pageMeta): string
    {
        $canonicalUrl = (string) ($pageMeta['canonicalUrl'] ?? url('/'));
        $personName = $this->cleanMetaText(
            Arr::get($portfolioData, 'about.name')
            ?: Arr::get($portfolioData, 'meta.name')
            ?: Arr::get($pageMeta, 'title', 'Portfolio')
        );
        $jobTitle = $this->cleanMetaText((string) Arr::get($portfolioData, 'meta.role', ''));
        $description = $this->cleanMetaText((string) Arr::get($pageMeta, 'description', ''));
        $imageUrl = $this->absoluteUrl((string) Arr::get($portfolioData, 'images.hero', ''), (string) Arr::get($pageMeta, 'ogImageUrl', ''));
        $sameAs = array_values(array_filter([
            $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.social.github', '')),
            $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.social.linkedin', '')),
            $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.social.twitter', '')),
            $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.social.dribbble', '')),
            $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.social.instagram', '')),
        ]));

        $knowsAbout = [];
        foreach ((array) ($portfolioData['skills'] ?? []) as $items) {
            foreach ((array) $items as $skill) {
                $name = $this->cleanMetaText((string) ($skill['name'] ?? ''));
                if ($name !== '') {
                    $knowsAbout[$name] = $name;
                }
            }
        }

        $graph = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                '@id' => $canonicalUrl.'#website',
                'url' => $canonicalUrl,
                'name' => $this->cleanMetaText((string) Arr::get($pageMeta, 'siteName', $personName)),
                'description' => $description,
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                '@id' => $canonicalUrl.'#webpage',
                'url' => $canonicalUrl,
                'name' => $this->cleanMetaText((string) Arr::get($pageMeta, 'title', $personName)),
                'description' => $description,
                'isPartOf' => [
                    '@id' => $canonicalUrl.'#website',
                ],
                'about' => [
                    '@id' => $canonicalUrl.'#person',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                '@id' => $canonicalUrl.'#person',
                'name' => $personName,
                'url' => $canonicalUrl,
                'image' => $imageUrl,
                'jobTitle' => $jobTitle,
                'description' => $description,
                'email' => Arr::get($portfolioData, 'contact.email')
                    ? 'mailto:'.$this->cleanMetaText((string) Arr::get($portfolioData, 'contact.email'))
                    : null,
                'telephone' => $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.phone', '')) ?: null,
                'address' => $this->cleanMetaText((string) Arr::get($portfolioData, 'contact.location', '')) ?: null,
                'sameAs' => $sameAs === [] ? null : $sameAs,
                'knowsAbout' => array_values($knowsAbout),
            ],
        ];

        return (string) json_encode(
            array_values(array_map(
                fn (array $item): array => array_filter($item, fn ($value) => $value !== null && $value !== [] && $value !== ''),
                $graph
            )),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }

    private function absoluteUrl(string $path, string $fallback = ''): string
    {
        $candidate = trim($path);

        if ($candidate === '' || str_starts_with($candidate, 'data:')) {
            return $fallback !== '' ? $fallback : url('/profile.png');
        }

        if (filter_var($candidate, FILTER_VALIDATE_URL)) {
            return $candidate;
        }

        return url('/'.ltrim($candidate, '/'));
    }

    private function joinUrl(string $base, string $path): string
    {
        return rtrim($base, '/').'/'.ltrim($path, '/');
    }

    private function renderPhpTemplate(string $path, array $data): string
    {
        extract($data, EXTR_SKIP);

        ob_start();
        include $path;

        return (string) ob_get_clean();
    }

    private function adminUser(): ?User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->is_admin) {
            return null;
        }

        return $user;
    }
}
