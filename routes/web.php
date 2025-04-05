<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
})->name('home');

// TODO: Añadir middleware para verificar autenticación y roles
/*Route::middleware(['auth:sanctum', 'role:dj'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');
});


Route::get('djsessions', [DjsessionController::class, 'index'])
    ->middleware(['auth', 'role:dj,user']); // compartida

Route::get('djsessions/{djsession}', [DjsessionController::class, 'show'])
    ->middleware(['auth', 'role:dj,user']); // compartida

Route::get('djsessions/create', [DjsessionController::class, 'create'])
    ->middleware(['auth', 'role:dj']); // solo DJ

Route::post('djsessions', [DjsessionController::class, 'store'])
    ->middleware(['auth', 'role:dj']); // solo DJ

Route::get('djsessions/{djsession}/edit', [DjsessionController::class, 'edit'])
    ->middleware(['auth', 'role:dj']); // solo DJ

Route::put('djsessions/{djsession}', [DjsessionController::class, 'update'])
    ->middleware(['auth', 'role:dj']); // solo DJ

Route::delete('djsessions/{djsession}', [DjsessionController::class, 'destroy'])
    ->middleware(['auth', 'role:dj']); // solo DJ

Route::get('/djsession/join/{id}', [DjsessionController::class, 'join'])
    ->middleware(['auth', 'role:dj,user']); // compartida
Route::get('/djsession/leave/{id}', [DjsessionController::class, 'leave'])
    ->middleware(['auth', 'role:dj,user']); // compartida
*/

Route::resource('djsessions', \App\Http\Controllers\DjsessionController::class)
    ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
Route::get('/djsession/join/{id}', [\App\Http\Controllers\DjsessionController::class, 'join'])
    ->name('djsession.join');
Route::get('/djsession/leave/{id}', [\App\Http\Controllers\DjsessionController::class, 'leave'])
    ->name('djsession.join');
Route::get('/djsession/search', [\App\Http\Controllers\DjsessionController::class, 'search']) 
    ->name('djsession.search'); 

Route::get('/djsession/exit', function () {
    return view('home');
})->name('djsession.exit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
