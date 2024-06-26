<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AccountController::class,'register']);
Route::post('login',[AccountController::class,'login']);
Route::post('logout',[AccountController::class,'logout']);
Route::middleware('auth:sanctum')->group(function(){
Route::get('user',[AccountController::class,'user']);
});