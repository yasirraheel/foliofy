<?php
/**
 * upload.php — Portfolio Image Upload Handler
 * Accepts: POST with multipart/form-data, field name "image"
 * Returns: JSON { success, url } or { error }
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $errCode = $_FILES['image']['error'] ?? -1;
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded or upload error: ' . $errCode]);
    exit;
}

$file      = $_FILES['image'];
$maxBytes  = 8 * 1024 * 1024; // 8 MB
$allowed   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

// Validate MIME type using finfo (more reliable than $_FILES['type'])
$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!array_key_exists($mimeType, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed.']);
    exit;
}

if ($file['size'] > $maxBytes) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large. Maximum size is 8 MB.']);
    exit;
}

// Ensure uploads directory exists
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate a safe unique filename
$ext      = $allowed[$mimeType];
$filename = 'img_' . bin2hex(random_bytes(8)) . '.' . $ext;
$destPath = $uploadDir . $filename;
$destUrl  = 'uploads/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save the uploaded file.']);
    exit;
}

// Overwrite the canonical static file so it's always accessible without localStorage
$imageKey = $_POST['imageKey'] ?? '';
if ($imageKey === 'hero' || $imageKey === '') {
    @copy($destPath, __DIR__ . '/profile.png');
}
if ($imageKey === 'about') {
    @copy($destPath, __DIR__ . '/profile.png'); // same photo used for about too
}

// Optionally delete old uploaded image for the same slot (key passed in POST)
if (!empty($_POST['replaces'])) {
    $old = basename($_POST['replaces']);
    $oldPath = $uploadDir . $old;
    if (file_exists($oldPath) && strpos($old, 'img_') === 0) {
        @unlink($oldPath);
    }
}

echo json_encode(['success' => true, 'url' => $destUrl]);
