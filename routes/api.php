<?php

use App\Http\Controllers\{TransactionController, UserController};
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);

Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{column}/{id}', [TransactionController::class, 'show']);
Route::post('/transactions', [TransactionController::class, 'store']);
