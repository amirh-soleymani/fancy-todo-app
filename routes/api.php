<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// Auth & Verification Routes
Route::post('/register', [AuthController::class, 'register']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])->name('verification.verify');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::prefix('/todo')->group( function() {

    Route::get('/index', [TodoController::class, 'index']);
    Route::post('/create', [TodoController::class, 'store']);
    Route::get('/show/{id}', [TodoController::class, 'show']);
    Route::put('/update/{id}', [TodoController::class, 'update']);
    Route::delete('/delete/{id}', [TodoController::class, 'destroy']);
})->middleware('auth:sanctum');

