<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ApplicationController;

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::delete('logout', [AuthController::class, 'logout']);

Route::group(['prefix' => 'v1'], function () {
    // Guest accessible
    Route::post('/applications', [ApplicationController::class, 'store']);
    // Admin only routes
    Route::group(['middleware' => ['admin.only']], function () {
        Route::get('/applications', [ApplicationController::class, 'index']);
        Route::post('/applications/{id}/process', [ApplicationController::class, 'statusProcess']);
        Route::post('/applications/{id}/on-hold', [ApplicationController::class, 'statusOnHold']);
        Route::post('/applications/{id}/reject', [ApplicationController::class, 'statusReject']);
        Route::post('/applications/{id}/accept', [ApplicationController::class, 'statusAccept']);
    });
});

