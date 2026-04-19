<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ];

    public function __invoke(Request $request): JsonResponse
    {
        if ($request->isMethod('options')) {
            return response()->json([], 200, self::CORS_HEADERS);
        }

        $action = (string) $request->query('action', 'save');

        if ($request->isMethod('get') && $action === 'get') {
            return response()->json($this->readMessages(), 200, self::CORS_HEADERS);
        }

        if ($request->isMethod('post') && $action === 'save') {
            $payload = $request->isJson() ? $request->json()->all() : $request->all();

            if (
                empty($payload['name']) ||
                empty($payload['email']) ||
                empty($payload['message'])
            ) {
                return response()->json(['error' => 'Missing required fields'], 400, self::CORS_HEADERS);
            }

            $messages = $this->readMessages();
            $messages[] = [
                'name' => $this->sanitize((string) $payload['name']),
                'email' => $this->sanitize((string) $payload['email']),
                'subject' => $this->sanitize((string) ($payload['subject'] ?? '')),
                'message' => $this->sanitize((string) $payload['message']),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'ip' => $request->ip() ?? '',
            ];

            $this->writeMessages($messages);

            return response()->json([
                'success' => true,
                'total' => count($messages),
            ], 200, self::CORS_HEADERS);
        }

        if ($request->isMethod('post') && $action === 'delete') {
            $index = (int) $request->query('index', -1);
            $messages = $this->readMessages();

            if ($index < 0 || $index >= count($messages)) {
                return response()->json(['error' => 'Invalid index'], 400, self::CORS_HEADERS);
            }

            array_splice($messages, $index, 1);
            $this->writeMessages($messages);

            return response()->json([
                'success' => true,
                'total' => count($messages),
            ], 200, self::CORS_HEADERS);
        }

        return response()->json(['error' => 'Unknown action'], 400, self::CORS_HEADERS);
    }

    private function readMessages(): array
    {
        $path = (string) config('portfolio.messages_path');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    private function writeMessages(array $messages): void
    {
        $path = (string) config('portfolio.messages_path');

        File::ensureDirectoryExists(dirname($path));
        File::put(
            $path,
            json_encode(array_values($messages), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function sanitize(string $value): string
    {
        return htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
    }
}
