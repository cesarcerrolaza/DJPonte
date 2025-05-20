<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    Route::get('/djsessions',[\App\Http\Controllers\DjsessionController::class, 'index'])
        ->name('djsessions.index');
    Route::get('/djsessions/search', [\App\Http\Controllers\DjsessionController::class, 'search']) 
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
    Route::get('/djsessions/join/{djsession}', [\App\Http\Controllers\DjsessionController::class, 'join'])
    ->name('djsessions.join');
    Route::get('/djsessions/leave/{djsession}', [\App\Http\Controllers\DjsessionController::class, 'leave'])
        ->name('djsessions.leave');

    Route::get('/djsessions/song-request',[\App\Http\Controllers\DjsessionController::class, 'index'])
    ->name('song-request');

    Route::get('/djsessions/tip',[\App\Http\Controllers\DjsessionController::class, 'index'])
    ->name('tip');

    Route::get('/djsessions/raffle',[\App\Http\Controllers\DjsessionController::class, 'index'])
    ->name('raffle');

    Route::get('/tips/success', [\App\Http\Controllers\TipController::class, 'success'])->name('tips.success');
    Route::get('/tips/cancel', [\App\Http\Controllers\TipController::class, 'cancel'])->name('tips.cancel');

});
 

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:dj'
])->group(function () {
    Route::resource('djsessions', \App\Http\Controllers\DjsessionController::class)
    ->only(['show', 'create', 'store', 'edit', 'update', 'destroy']);
});
