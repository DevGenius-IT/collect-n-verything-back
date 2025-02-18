<?php

use Illuminate\Support\Facades\Route;

use App\Http\Modules\Authentication\AuthController;

// Authentication endpoints
Route::prefix("auth")->group(function () {
  // Basic authentication
  Route::post("/signup", [AuthController::class, "signUp"]);
  Route::post("/signin", [AuthController::class, "signIn"]);
  Route::get("/verify", [AuthController::class, "verify"])->middleware("auth:sanctum");
  Route::get("/signout", [AuthController::class, "signOut"])->middleware("auth:sanctum");
  Route::post("/forgot-password/{method}", [AuthController::class, "forgotPassword"]);
  Route::post("/reset-password/{token}", [AuthController::class, "resetPassword"])->name(
    "password.reset"
  );
});

Route::prefix("oauth")
  ->middleware("web")
  ->group(function () {
    // OAuth authentication
    Route::get("/{provider}/redirect", [AuthController::class, "redirectToProvider"]);
    Route::get("/{provider}/callback", [AuthController::class, "handleProviderCallback"]);
  });
