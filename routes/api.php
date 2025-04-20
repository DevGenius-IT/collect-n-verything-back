<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix("api")->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('packs', PackController::class);
});

