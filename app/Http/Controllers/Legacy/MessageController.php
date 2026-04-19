<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use App\Support\PortfolioDataStore;
use App\Support\WhatsAppNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ];

    public function __construct(
        private readonly PortfolioDataStore $portfolioDataStore,
        private readonly WhatsAppNotifier $whatsAppNotifier,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        if ($request->isMethod('options')) {
            return response()->json([], 200, self::CORS_HEADERS);
        }

        $action = (string) $request->query('action', 'save');

        if ($request->isMethod('get') && $action === 'get') {
            if (! $this->adminUser()) {
                return response()->json(['error' => 'Unauthenticated.'], 401, self::CORS_HEADERS);
            }

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

            $message = ContactMessage::query()->create([
                'name' => $this->sanitize((string) $payload['name']),
                'email' => $this->sanitize((string) $payload['email']),
                'subject' => $this->sanitize((string) ($payload['subject'] ?? '')),
                'message' => $this->sanitize((string) $payload['message']),
                'ip' => $request->ip() ?? '',
            ]);

            $messages = $this->readMessages();
            $this->whatsAppNotifier->sendContactMessage(
                $this->portfolioDataStore->full(),
                [
                    'name' => $message->name,
                    'email' => $message->email,
                    'subject' => $message->subject,
                    'message' => $message->message,
                ]
            );

            return response()->json([
                'success' => true,
                'total' => count($messages),
            ], 200, self::CORS_HEADERS);
        }

        if ($request->isMethod('post') && $action === 'delete') {
            if (! $this->adminUser()) {
                return response()->json(['error' => 'Unauthenticated.'], 401, self::CORS_HEADERS);
            }

            $id = (int) $request->query('id', 0);
            $message = $id > 0
                ? ContactMessage::query()->find($id)
                : $this->messageByIndex((int) $request->query('index', -1));

            if (! $message) {
                return response()->json(['error' => 'Invalid message'], 400, self::CORS_HEADERS);
            }

            $message->delete();

            return response()->json([
                'success' => true,
                'total' => ContactMessage::query()->count(),
            ], 200, self::CORS_HEADERS);
        }

        return response()->json(['error' => 'Unknown action'], 400, self::CORS_HEADERS);
    }

    private function readMessages(): array
    {
        return ContactMessage::query()
            ->orderBy('id')
            ->get()
            ->map(fn (ContactMessage $message): array => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'subject' => $message->subject ?? '',
                'message' => $message->message,
                'timestamp' => $message->created_at?->format('Y-m-d H:i:s') ?? '',
                'ip' => $message->ip ?? '',
            ])
            ->all();
    }

    private function sanitize(string $value): string
    {
        return htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
    }

    private function messageByIndex(int $index): ?ContactMessage
    {
        if ($index < 0) {
            return null;
        }

        return ContactMessage::query()
            ->orderBy('id')
            ->skip($index)
            ->first();
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
