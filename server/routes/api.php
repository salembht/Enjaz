<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AccountController::class,'register']);
Route::post('login',[AccountController::class,'login'])->name('login');

Route::middleware('auth:sanctum')->group(function(){
Route::get('user',[AccountController::class,'user']);
Route::post('logout',[AccountController::class,'logout']);
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
    Route::post('/complete/{id}', [TaskController::class, 'complete']);
    Route::post('/uncomplete/{id}', [TaskController::class, 'uncomplete']);
});
});