<?php

use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
   Route::post('/register', [RegisterUserController::class, 'store']);
});
