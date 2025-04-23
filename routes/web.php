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
    Route::get('/djsessions/join/{djsession}', [\App\Http\Controllers\DjsessionController::class, 'join'])
    ->name('djsessions.join');
    Route::get('/djsessions/leave/{djsession}', [\App\Http\Controllers\DjsessionController::class, 'leave'])
        ->name('djsessions.leave');
});
 