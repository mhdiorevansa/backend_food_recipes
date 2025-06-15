<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
   Route::post('/register', [AuthenticationController::class, 'register']);
   Route::post('/login', [AuthenticationController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {
   Route::get('/logout', [AuthenticationController::class, 'logout']);
});
