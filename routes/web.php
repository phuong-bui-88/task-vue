<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/upload-image', [\App\Http\Controllers\UploadImageController::class, 'update']);
Route::delete('/upload-image', [\App\Http\Controllers\UploadImageController::class, 'destroy']);

Route::get('/auth/google', [\App\Http\Controllers\Api\UserController::class, 'redirectToGoogle']);

Route::get('/auth/google/callback', [\App\Http\Controllers\Api\UserController::class, 'handleGoogleCallback']);

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
