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


// TODO: Añadir middleware para verificar autenticación y roles
/*Route::middleware(['auth:sanctum', 'role:dj'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');
});
*/

Route::get('/djsession/exit', function () {
    return view('home');
})->name('djsession.exit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('djsessions', \App\Http\Controllers\DjsessionController::class)
    ->only(['index']);
    Route::get('/djsession/search', [\App\Http\Controllers\DjsessionController::class, 'search']) 
        ->name('djsession.search');

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:user'
])->group(function () {
    Route::get('/djsession/join/{id}', [\App\Http\Controllers\DjsessionController::class, 'join'])
    ->name('djsession.join');
    Route::get('/djsession/leave/{id}', [\App\Http\Controllers\DjsessionController::class, 'leave'])
        ->name('djsession.leave');
});
 