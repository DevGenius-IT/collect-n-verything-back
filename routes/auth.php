<?php

use Illuminate\Support\Facades\Route;

use App\Http\Modules\Authentication\AuthController;

// Authentication endpoints
Route::prefix("auth")->group(function () {
  // Basic authentication
  Route::post("/signup", [AuthController::class, "signUp"]);
  Route::post("/signin", [AuthController::class, "signIn"]);
  Route::get("/signout", [AuthController::class, "signOut"])->middleware("auth:sanctum");
  Route::post("/reset-password", [AuthController::class, "resetPassword"]);
});
