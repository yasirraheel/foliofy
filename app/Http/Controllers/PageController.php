<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PortfolioDataStore;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
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

    private function htmlResponse(string $viewName, array $portfolioData, array $adminContext): Response
    {
        $html = File::get(resource_path("views/{$viewName}.blade.php"));
        $csrfToken = csrf_token();
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

        $html = $this->applyAssetVersioning($html);

        $script = sprintf(
            '<meta name="csrf-token" content="%s">%s<script>window.__PORTFOLIO_DATA__=%s;window.__ADMIN_CONTEXT__=%s;window.__CSRF_TOKEN__=%s;</script>%s</head>',
            e($csrfToken),
            PHP_EOL,
            json_encode($portfolioData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($adminContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            json_encode($csrfToken, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
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
        $path = public_path($asset);

        if (! File::exists($path)) {
            return '1';
        }

        $hash = sha1_file($path);

        if ($hash === false) {
            return (string) File::lastModified($path);
        }

        return substr($hash, 0, 10);
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
