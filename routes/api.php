<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('api.')->prefix('v1')->group(function () {
    Route::get('/positions', PositionController::class);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::middleware('custom_auth_jwt_api')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
    });

    Route::get('/token', [AuthController::class, 'getToken']);
});
