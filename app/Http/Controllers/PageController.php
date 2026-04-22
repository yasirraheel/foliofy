<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\OgImageGenerator;
use App\Support\PortfolioDataStore;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
        private readonly OgImageGenerator $ogImageGenerator,
    ) {
    }

    public function portfolio(): Response
    {
        return $this->htmlResponse(
            'portfolio',
            $this->portfolioDataStore->public(),
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

    private function htmlResponse(string $viewName, array $portfolioData, array $adminContext): Response
    {
        $html = File::get(resource_path("views/{$viewName}.blade.php"));
        $csrfToken = csrf_token();
        $pageMeta = $viewName === 'portfolio'
            ? $this->portfolioPageMeta($portfolioData)
            : [];
        $themeDefault = in_array($portfolioData['meta']['themeDefault'] ?? null, ['dark', 'light'], true)
            ? $portfolioData['meta']['themeDefault']
            : 'dark';

        if ($viewName === 'portfolio') {
            $html = str_replace(
                '<html lang="en" data-theme="dark">',
                sprintf('<html lang="en" data-theme="%s">', e($themeDefault)),
                $html
            );
        }

        if ($viewName === 'portfolio') {
            $html = $this->applyPortfolioMeta($html, $pageMeta);
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

    private function applyPortfolioMeta(string $html, array $pageMeta): string
    {
        return strtr($html, [
            '__PORTFOLIO_TITLE__' => e($pageMeta['title'] ?? ''),
            '__PORTFOLIO_DESCRIPTION__' => e($pageMeta['description'] ?? ''),
            '__PORTFOLIO_KEYWORDS__' => e($pageMeta['keywords'] ?? ''),
            '__PORTFOLIO_AUTHOR__' => e($pageMeta['author'] ?? ''),
            '__PORTFOLIO_SITE_NAME__' => e($pageMeta['siteName'] ?? ''),
            '__PORTFOLIO_CANONICAL_URL__' => e($pageMeta['canonicalUrl'] ?? ''),
            '__PORTFOLIO_OG_IMAGE_URL__' => e($pageMeta['ogImageUrl'] ?? ''),
            '__PORTFOLIO_OG_IMAGE_ALT__' => e($pageMeta['ogImageAlt'] ?? ''),
            '__PORTFOLIO_OG_IMAGE_WIDTH__' => (string) ($pageMeta['ogImageWidth'] ?? 1200),
            '__PORTFOLIO_OG_IMAGE_HEIGHT__' => (string) ($pageMeta['ogImageHeight'] ?? 630),
        ]);
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

    private function adminUser(): ?User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->is_admin) {
            return null;
        }

        return $user;
    }
}
