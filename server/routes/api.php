<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AccountController::class,'register']);
Route::post('login',[AccountController::class,'login']);
Route::post('logout',[AccountController::class,'logout']);
Route::middleware('auth:sanctum')->group(function(){
Route::get('user',[AccountController::class,'user']);
Route::prefix('api/tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/{id}/uncomplete', [TaskController::class, 'uncomplete'])->name('tasks.uncomplete');
});
});