<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class WhatsAppProxyController extends Controller
{
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ];

    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('options')) {
            return response('', 200, self::CORS_HEADERS);
        }

        if (! $request->isMethod('post')) {
            return response()->json(['error' => 'Method not allowed'], 405, self::CORS_HEADERS);
        }

        $payload = $request->isJson() ? $request->json()->all() : $request->all();

        if (
            empty($payload['apiKey']) ||
            empty($payload['accountName']) ||
            empty($payload['number']) ||
            empty($payload['message'])
        ) {
            return response()->json([
                'error' => 'Missing required fields: apiKey, accountName, number, message',
            ], 400, self::CORS_HEADERS);
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Api-Key' => (string) $payload['apiKey'],
                ])
                ->asJson()
                ->post((string) config('portfolio.whatsapp_url'), [
                    'account_name' => (string) $payload['accountName'],
                    'number' => (string) $payload['number'],
                    'message' => (string) $payload['message'],
                ]);
        } catch (Throwable $exception) {
            return response()->json([
                'error' => 'cURL error: '.$exception->getMessage(),
            ], 502, self::CORS_HEADERS);
        }

        return response(
            $response->body(),
            $response->status(),
            array_merge(self::CORS_HEADERS, [
                'Content-Type' => $response->header('Content-Type', 'application/json'),
            ])
        );
    }
}
