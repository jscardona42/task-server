<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/users/login', [AuthController::class, 'login']);
Route::post('/users', [AuthController::class, 'store']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/show/{id}', [TaskController::class, 'show']);
    Route::get('/tasks/byuser/{id}', [TaskController::class, 'byUser']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/update/{id}', [TaskController::class, 'update']);
    Route::put('/tasks/delete/{id}', [TaskController::class, 'delete']);

    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/users/show/{id}', [AuthController::class, 'show']);
    Route::put('/users/update/{id}', [AuthController::class, 'update']);
    Route::post('/users/logout', [AuthController::class, 'logout']);
});
