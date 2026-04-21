<?php

namespace App\Http\Controllers\Legacy;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type',
    ];

    public function __invoke(Request $request): JsonResponse
    {
        if ($request->isMethod('options')) {
            return response()->json([], 200, self::CORS_HEADERS);
        }

        if (! $request->isMethod('post')) {
            return response()->json(['error' => 'Method not allowed'], 405, self::CORS_HEADERS);
        }

        if (! $this->adminUser()) {
            return response()->json(['error' => 'Unauthenticated.'], 401, self::CORS_HEADERS);
        }

        $field = $request->hasFile('image')
            ? 'image'
            : ($request->hasFile('file') ? 'file' : null);

        if ($field === null) {
            $errorCode = $_FILES['image']['error'] ?? ($_FILES['file']['error'] ?? -1);

            return response()->json([
                'error' => 'No file uploaded or upload error: '.$errorCode,
            ], 400, self::CORS_HEADERS);
        }

        $file = $request->file($field);

        if ($file === null || ! $file->isValid()) {
            $errorCode = $file?->getError() ?? ($_FILES[$field]['error'] ?? -1);

            return response()->json([
                'error' => 'No file uploaded or upload error: '.$errorCode,
            ], 400, self::CORS_HEADERS);
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
        ];

        $mimeType = (string) $file->getMimeType();

        if (! array_key_exists($mimeType, $allowed)) {
            return response()->json([
                'error' => 'Invalid file type. Only JPG, PNG, GIF, WEBP, and PDF allowed.',
            ], 400, self::CORS_HEADERS);
        }

        if (($file->getSize() ?? 0) > (8 * 1024 * 1024)) {
            return response()->json([
                'error' => 'File too large. Maximum size is 8 MB.',
            ], 400, self::CORS_HEADERS);
        }

        $uploadDir = (string) config('portfolio.uploads_path');
        File::ensureDirectoryExists($uploadDir);

        $isImage = str_starts_with($mimeType, 'image/');
        $filenamePrefix = $isImage ? 'img_' : 'cv_';
        $filename = $filenamePrefix.bin2hex(random_bytes(8)).'.'.$allowed[$mimeType];
        $file->move($uploadDir, $filename);

        $destPath = $uploadDir.DIRECTORY_SEPARATOR.$filename;
        $destUrl = 'uploads/'.$filename;

        $assetKey = (string) $request->input('assetKey', $request->input('imageKey', ''));
        if (
            $isImage &&
            ($assetKey === 'hero' || $assetKey === '' || $assetKey === 'about')
        ) {
            File::copy($destPath, (string) config('portfolio.profile_image_path'));
        }

        $replaces = basename((string) $request->input('replaces', ''));
        if ($replaces !== '' && ($isImage ? str_starts_with($replaces, 'img_') : str_starts_with($replaces, 'cv_'))) {
            $oldPath = $uploadDir.DIRECTORY_SEPARATOR.$replaces;
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        return response()->json([
            'success' => true,
            'url' => $destUrl,
        ], 200, self::CORS_HEADERS);
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
