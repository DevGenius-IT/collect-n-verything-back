<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("/signup", [AuthController::class, "signUp"]);
    Route::post("/signin", [AuthController::class, "signIn"]);
    Route::get("/signout", [AuthController::class, "signOut"])->middleware("auth:sanctum");
    Route::post("/reset-password", [AuthController::class, "resetPassword"]);
});
