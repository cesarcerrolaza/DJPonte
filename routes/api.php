<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramWebhookController;
use App\Http\Controllers\StripeController;
use App\Http\Middleware\VerifyFacebookSignature;
use App\Http\Controllers\SocialController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/instagram/webhook', [InstagramWebhookController::class, 'handle'])
    ->middleware(VerifyFacebookSignature::class)->name('instagram.webhook');

Route::get('/instagram/webhook', [InstagramWebhookController::class, 'handle'])->name('instagram.webhook');

Route::match(['get', 'post'], '/stripe/webhook',  [StripeController::class, 'handle'])->name('stripe.webhook');

Route::post('/data-deletion', [SocialController::class, 'handleDataDeletion']);

Route::get('/data-deletion/status/{confirmation_code}', [SocialController::class, 'showDeletionStatus'])->name('data-deletion.status');

