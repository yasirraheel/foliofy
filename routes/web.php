<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminPortfolioController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Legacy\MessageController;
use App\Http\Controllers\Legacy\UploadController;
use App\Http\Controllers\Legacy\WhatsAppProxyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'portfolio']);
Route::get('/index.html', [PageController::class, 'portfolio']);
Route::get('/admin', [PageController::class, 'admin']);
Route::get('/admin.html', [PageController::class, 'admin']);

Route::get('/admin/bootstrap', [AdminAuthController::class, 'bootstrap']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
Route::post('/admin/password', [AdminAuthController::class, 'updatePassword']);
Route::post('/admin/portfolio-data', [AdminPortfolioController::class, 'store']);

Route::match(['GET', 'POST', 'OPTIONS'], '/api_messages.php', MessageController::class);
Route::match(['GET', 'POST', 'OPTIONS'], '/upload.php', UploadController::class);
Route::match(['GET', 'POST', 'OPTIONS'], '/wa_proxy.php', WhatsAppProxyController::class);
