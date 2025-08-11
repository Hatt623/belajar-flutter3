<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\PostController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware('auth:sanctum')->group(function() {
//     Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

//     Route::resource('/posts', \App\Http\Controllers\Api\PostController::class);
// });
