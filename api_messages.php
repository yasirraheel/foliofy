<?php
/**
 * api_messages.php — Portfolio Contact Form Message Store
 * Handles saving, fetching, and deleting submissions.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

define('DATA_FILE', __DIR__ . '/messages.json');

function readMessages() {
    if (!file_exists(DATA_FILE)) return [];
    $raw = file_get_contents(DATA_FILE);
    return json_decode($raw, true) ?: [];
}

function writeMessages(array $msgs) {
    file_put_contents(DATA_FILE, json_encode(array_values($msgs), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$action = $_GET['action'] ?? 'save';

/* ── GET: return all messages ── */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    echo json_encode(readMessages());
    exit;
}

/* ── POST: save a new message ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body || empty($body['name']) || empty($body['email']) || empty($body['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    $msgs = readMessages();
    $msgs[] = [
        'name'      => htmlspecialchars(strip_tags($body['name']),    ENT_QUOTES),
        'email'     => htmlspecialchars(strip_tags($body['email']),   ENT_QUOTES),
        'subject'   => htmlspecialchars(strip_tags($body['subject'] ?? ''), ENT_QUOTES),
        'message'   => htmlspecialchars(strip_tags($body['message']), ENT_QUOTES),
        'timestamp' => date('Y-m-d H:i:s'),
        'ip'        => $_SERVER['REMOTE_ADDR'] ?? ''
    ];
    writeMessages($msgs);
    echo json_encode(['success' => true, 'total' => count($msgs)]);
    exit;
}

/* ── POST: delete a message by index ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $idx  = intval($_GET['index'] ?? -1);
    $msgs = readMessages();
    if ($idx < 0 || $idx >= count($msgs)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid index']);
        exit;
    }
    array_splice($msgs, $idx, 1);
    writeMessages($msgs);
    echo json_encode(['success' => true, 'total' => count($msgs)]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
