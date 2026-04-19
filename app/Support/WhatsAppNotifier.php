<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Throwable;

class WhatsAppNotifier
{
    public function sendContactMessage(array $portfolioData, array $message): void
    {
        $config = $portfolioData['contact']['whatsappApi'] ?? [];

        if (
            ! is_array($config) ||
            empty($config['enabled']) ||
            empty($config['apiKey']) ||
            empty($config['accountName']) ||
            empty($config['targetNumber'])
        ) {
            return;
        }

        $body =
            "📬 *New Portfolio Contact!*\n\n".
            '*Name:* '.($message['name'] ?? '')."\n".
            '*Email:* '.($message['email'] ?? '')."\n".
            (! empty($message['subject']) ? '*Subject:* '.($message['subject'] ?? '')."\n" : '').
            "*Message:*\n".($message['message'] ?? '');

        try {
            Http::timeout(15)
                ->withHeaders([
                    'X-Api-Key' => (string) $config['apiKey'],
                ])
                ->asJson()
                ->post((string) config('portfolio.whatsapp_url'), [
                    'account_name' => (string) $config['accountName'],
                    'number' => (string) $config['targetNumber'],
                    'message' => $body,
                ]);
        } catch (Throwable) {
            // Message saving should still succeed even if the third-party API fails.
        }
    }

    public function sendCustomMessage(array $config, string $message, ?string $number = null): array
    {
        $response = Http::timeout(15)
            ->withHeaders([
                'X-Api-Key' => (string) ($config['apiKey'] ?? ''),
            ])
            ->asJson()
            ->post((string) config('portfolio.whatsapp_url'), [
                'account_name' => (string) ($config['accountName'] ?? ''),
                'number' => (string) ($number ?: ($config['targetNumber'] ?? '')),
                'message' => $message,
            ]);

        return [
            'status' => $response->status(),
            'body' => $response->body(),
            'content_type' => $response->header('Content-Type', 'application/json'),
        ];
    }
}
