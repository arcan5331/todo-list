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


Route::apiResource('tasks', \App\Http\Controllers\TaskController::class, ['middleware' => 'auth:sanctum']);
Route::apiResource('tags', \App\Http\Controllers\TagController::class, ['middleware' => 'auth:sanctum']);
Route::apiResource('categories', \App\Http\Controllers\CategoryController::class, ['middleware' => 'auth:sanctum']);

Route::group([
    'prefix' => 'auth',
    'controller' => \App\Http\Controllers\AuthController::class,
], function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
