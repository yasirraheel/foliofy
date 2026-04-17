<?php
/**
 * wa_proxy.php — WhatsApp API Proxy
 * Receives POST from the frontend JS and forwards it server-to-server
 * to wa-server.shahabtech.com (bypassing browser CORS restrictions).
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST')    { http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit; }

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data || empty($data['apiKey']) || empty($data['accountName']) || empty($data['number']) || empty($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: apiKey, accountName, number, message']);
    exit;
}

$apiKey      = $data['apiKey'];
$accountName = $data['accountName'];
$number      = $data['number'];
$message     = $data['message'];

$payload = json_encode([
    'account_name' => $accountName,
    'number'       => $number,
    'message'      => $message
]);

$ch = curl_init('https://wa-server.shahabtech.com/api/v1/send-message');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-Api-Key: ' . $apiKey
    ],
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(502);
    echo json_encode(['error' => 'cURL error: ' . $error]);
    exit;
}

http_response_code($httpCode);
echo $response;
