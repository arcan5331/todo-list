<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['verified', 'auth:sanctum']], function () {
    Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);
    Route::apiResource('tags', \App\Http\Controllers\TagController::class);
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
});

Route::group([
    'prefix' => 'auth',
    'controller' => \App\Http\Controllers\AuthController::class,
], function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/email/verify/{id}/{hash}/{code}', 'verifyEmail')
        ->middleware('signed')->name('verification.verify.user');
    Route::post('/email/verification-notification', 'sendEmailVerificationNotification')
        ->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
});
