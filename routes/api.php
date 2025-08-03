<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Middleware\VerifyFacebookSignature;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/instagram/webhook', [InstagramWebhookController::class, 'handle'])
    ->middleware(VerifyFacebookSignature::class);

Route::get('/instagram/webhook', [InstagramWebhookController::class, 'handle']);

Route::match(['get', 'post'], '/stripe/webhook',  [StripeWebhookController::class, 'handle'])->name('stripe.webhook');