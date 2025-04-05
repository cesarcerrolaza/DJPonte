<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::match(['get', 'post'], '/instagram/webhook',  [InstagramWebhookController::class, 'handle'])->name('instagram.webhook');