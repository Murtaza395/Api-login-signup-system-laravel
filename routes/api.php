<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\POSTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('signup',[AuthController::class,'signup'])->name('auth.signup');
Route::post('login',[AuthController::class,'login'])->name('auth.login');
Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout',[AuthController::class,'logout'])->name('auth.logout');
    Route::apiResource('posts',POSTController::class);
});


