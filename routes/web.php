<?php

use App\Http\Controllers\Legacy\MessageController;
use App\Http\Controllers\Legacy\UploadController;
use App\Http\Controllers\Legacy\WhatsAppProxyController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

$htmlResponse = static function (string $name): Response {
    return response(
        File::get(resource_path("views/{$name}.blade.php")),
        200,
        ['Content-Type' => 'text/html; charset=UTF-8']
    );
};

Route::get('/', fn () => $htmlResponse('portfolio'));
Route::get('/index.html', fn () => $htmlResponse('portfolio'));
Route::get('/admin', fn () => $htmlResponse('admin'));
Route::get('/admin.html', fn () => $htmlResponse('admin'));

Route::match(['GET', 'POST', 'OPTIONS'], '/api_messages.php', MessageController::class);
Route::match(['GET', 'POST', 'OPTIONS'], '/upload.php', UploadController::class);
Route::match(['GET', 'POST', 'OPTIONS'], '/wa_proxy.php', WhatsAppProxyController::class);
