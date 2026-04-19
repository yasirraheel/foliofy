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
        ]);
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
