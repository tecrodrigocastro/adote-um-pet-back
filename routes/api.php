<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

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
    Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
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

Route::middleware('auth:sanctum')->prefix('adoptions')->group(function () {
    Route::get('/', [AdoptionController::class, 'index']);
    Route::post('/pets/{pet}', [AdoptionController::class, 'store']);
    Route::get('/{adoption}', [AdoptionController::class, 'show']);
    Route::patch('/{adoption}/approve', [AdoptionController::class, 'approve']);
    Route::patch('/{adoption}/reject', [AdoptionController::class, 'reject']);
    Route::get('/my/requests', [AdoptionController::class, 'myRequests']);
    Route::get('/received/requests', [AdoptionController::class, 'receivedRequests']);
});

Route::middleware('auth:sanctum')->prefix('addresses')->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::post('/', [AddressController::class, 'store']);
    Route::get('/{address}', [AddressController::class, 'show']);
    Route::put('/{address}', [AddressController::class, 'update']);
    Route::delete('/{address}', [AddressController::class, 'destroy']);
});

// Rotas públicas para organizações
Route::prefix('organizations')->group(function () {
    Route::get('/', [OrganizationController::class, 'index']);
    Route::get('/{organization}', [OrganizationController::class, 'show']);
    Route::get('/{organization}/pets', [OrganizationController::class, 'pets']);
});

// Rotas autenticadas para organizações
Route::middleware('auth:sanctum')->prefix('organizations')->group(function () {
    Route::put('/{organization}', [OrganizationController::class, 'update']);
    Route::get('/{organization}/statistics', [OrganizationController::class, 'statistics']);
    Route::post('/{organization}/verify', [OrganizationController::class, 'verify']); // Admin only

    // Gestão de voluntários
    Route::prefix('{organization}/volunteers')->group(function () {
        Route::get('/', [VolunteerController::class, 'index']);
        Route::post('/invite', [VolunteerController::class, 'invite']);
        Route::get('/{volunteer}', [VolunteerController::class, 'show']);
        Route::put('/{volunteer}', [VolunteerController::class, 'update']);
        Route::delete('/{volunteer}', [VolunteerController::class, 'destroy']);
        Route::post('/leave', [VolunteerController::class, 'leave']);
    });
});

// Rotas para voluntários
Route::middleware('auth:sanctum')->prefix('volunteers')->group(function () {
    Route::get('/my-organizations', [VolunteerController::class, 'myOrganizations']);
});
