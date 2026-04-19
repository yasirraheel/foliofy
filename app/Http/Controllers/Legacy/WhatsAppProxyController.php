<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PortfolioDataStore;
use App\Support\WhatsAppNotifier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WhatsAppProxyController extends Controller
{
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ];

    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
        private readonly WhatsAppNotifier $whatsAppNotifier,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('options')) {
            return response('', 200, self::CORS_HEADERS);
        }

        if (! $request->isMethod('post')) {
            return response()->json(['error' => 'Method not allowed'], 405, self::CORS_HEADERS);
        }

        if (! $this->adminUser()) {
            return response()->json(['error' => 'Unauthenticated.'], 401, self::CORS_HEADERS);
        }

        $payload = $request->isJson() ? $request->json()->all() : $request->all();
        $config = $this->portfolioDataStore->full()['contact']['whatsappApi'] ?? [];

        if (
            ! is_array($config) ||
            empty($config['apiKey']) ||
            empty($config['accountName']) ||
            empty($config['targetNumber']) ||
            empty($payload['message'])
        ) {
            return response()->json([
                'error' => 'Missing required WhatsApp configuration or message.',
            ], 400, self::CORS_HEADERS);
        }

        try {
            $response = $this->whatsAppNotifier->sendCustomMessage(
                $config,
                (string) $payload['message'],
                ! empty($payload['number']) ? (string) $payload['number'] : null
            );
        } catch (Throwable $exception) {
            return response()->json([
                'error' => 'cURL error: '.$exception->getMessage(),
            ], 502, self::CORS_HEADERS);
        }

        return response(
            $response['body'],
            $response['status'],
            array_merge(self::CORS_HEADERS, [
                'Content-Type' => $response['content_type'],
            ])
        );
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
