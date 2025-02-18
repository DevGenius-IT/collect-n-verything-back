<?php

use App\Enums\RolesEnum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for("v1", function (Request $request) {
  return Limit::perSecond(20)->by($request->user()?->id ?: $request->ip());
});

Route::get("/healthcheck", function () {
  return response()->json([
    "message" => __("global.welcome", [
      "name" => env("APP_NAME"),
      "version" => env("API_VERSION", "v1"),
    ]),
  ]);
});

// Authentication endpoints
include base_path("routes/auth.php");

// Admin endpoints
Route::prefix("admin")
  ->middleware(["crud", "role:" . RolesEnum::SUPER_ADMIN->value . "|" . RolesEnum::ADMIN->value])
  ->group(function () {
    $adminRoutes = config("app.endpoints.admin");

    foreach ($adminRoutes as $route) {
      include base_path("routes/admin/{$route}.php");
    }
  });
