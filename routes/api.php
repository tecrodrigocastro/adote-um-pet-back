<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\User\UserController;



Route::middleware('auth:sanctum')->prefix('pets')->group(function () {
    Route::get('/', [PetController::class, 'index']);
    Route::post('/', [PetController::class, 'store']);
    Route::get('/{pet}', [PetController::class, 'show']);
    Route::put('/{pet}', [PetController::class, 'update']);
    Route::delete('/{pet}', [PetController::class, 'destroy']);
    Route::post('/{pet}/photos', [PetController::class, 'updatePhotos']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::put('/', [UserController::class, 'updateUser']);
    Route::post('/photo/{user}', [UserController::class, 'updatePhoto']);
});

Route::middleware('auth:sanctum')->prefix('chats')->group(function () {
    Route::get('/', [ChatController::class, 'index']);
    Route::get('/{chat}', [ChatController::class, 'show']);
    Route::post('/', [ChatController::class, 'store']);
});

Route::middleware('auth:sanctum')->prefix('messages')->group(function () {
    //Route::get('/', [ChatController::class, 'index']);
    Route::post('/', [MessageController::class, 'store']);
});
