<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest.api')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware('auth.api')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::/*middleware('unauthorized.api')->*/can('manipulate-todo', 'todo')->group(function () {
        Route::get('/todos/{todo}', [TodoController::class, 'show']);
        Route::put('/todos/{todo}', [TodoController::class, 'update']);
        Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
    });
});