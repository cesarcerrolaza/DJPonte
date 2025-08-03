<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DjsessionController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TipController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/register', function () {
    return view('welcome');
})->name('register');

Route::get('/loader', function () {
    return view('loader');
})->name('loader');

// TODO: Añadir middleware para verificar autenticación y roles
/*Route::middleware(['auth:sanctum', 'role:dj'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');
});
*/

Route::get('/djsessions/exit', function () {
    return view('home');
})->name('djsessions.exit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/djsessions',[DjsessionController::class, 'index'])
        ->name('djsessions.index');
    Route::get('/djsessions/search', [DjsessionController::class, 'search']) 
        ->name('djsessions.search');
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');
    Route::get('/tip/{id}', function ($id) {
        return view('loader', ['type' => 'tip','id' => $id]);
    })->name('tip.id');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:user'
])->group(function () {
    Route::get('/djsessions/join/{djsession}', [DjsessionController::class, 'join'])
    ->name('djsessions.join');
    Route::get('/djsessions/leave/{djsession}', [DjsessionController::class, 'leave'])
        ->name('djsessions.leave');

    Route::get('/djsessions/song-request',[DjsessionController::class, 'index'])
    ->name('song-request');

    Route::get('/djsessions/tip',[DjsessionController::class, 'index'])
    ->name('tip');

    Route::get('/djsessions/raffle',[DjsessionController::class, 'index'])
    ->name('raffle');

    Route::get('/tips/success', [TipController::class, 'success'])->name('tips.success');
    Route::get('/tips/cancel', [TipController::class, 'cancel'])->name('tips.cancel');

});
 

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:dj'
])->group(function () {
    Route::resource('djsessions', DjsessionController::class)
    ->only(['show', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/instagram/connect', [SocialController::class, 'connectInstagram'])
        ->name('instagram.connect');
        Route::get('/instagram/reconnect', [SocialController::class, 'reconnectInstagram'])
        ->name('instagram.reconnect');
    Route::get('/tiktok/connect', [SocialController::class, 'connectTikTok'])
        ->name('tiktok.connect');

    Route::get('/social', [SocialController::class, 'showPostGallery']) // Asegura que solo DJs logueados puedan verla
        ->name('socialManagement');
    Route::post('/set-monitored-post', [SocialController::class, 'setMonitoredPost'])
        ->name('setMonitoredPost');
});
Route::get('/instagram/callback', [SocialController::class, 'handleInstagramCallback'])
->name('instagram.callback');

Route::get('/tiktok/callback', [SocialController::class, 'handleTikTokCallback'])
->name('tiktok.callback');



