<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', \App\Http\Controllers\Api\TaskController::class);
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class)->except('store');

    Route::post('/favorite/{task}', [\App\Http\Controllers\Api\FavoriteController::class, 'store']);
    Route::delete('/favorite/{task}', [\App\Http\Controllers\Api\FavoriteController::class, 'destroy']);
});

Route::get('user/logout', [\App\Http\Controllers\Api\UserController::class, 'logout']);

Route::post('users', [\App\Http\Controllers\Api\UserController::class, 'store']);
Route::post('user/login', [\App\Http\Controllers\Api\UserController::class, 'login']);

Route::post('user/forgot-password', [\App\Http\Controllers\Api\UserController::class, 'forgotPassword'])->name('forgot.password');
Route::post('user/reset-password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword'])->name('reset.password');
