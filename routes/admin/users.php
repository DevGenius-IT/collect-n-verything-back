<?php

use App\Enums\PermissionsEnum;
use Illuminate\Support\Facades\Route;

use App\Http\Modules\Admin\Users\UserController;

// Users endpoints
Route::middleware("auth:sanctum")->group(function () {
  Route::prefix("users")->group(function () {
    Route::middleware("permission:" . PermissionsEnum::ADMIN_SHOW->value)->group(function () {
      Route::get("/", [UserController::class, "index"])->name("users.index");
      Route::get("/{user}", [UserController::class, "show"])->name("users.show");
    });
    Route::post("/", [UserController::class, "store"])
      ->middleware("permission:" . PermissionsEnum::ADMIN_STORE->value)
      ->name("users.store");
    Route::put("/{user}", [UserController::class, "update"])
      ->middleware("permission:" . PermissionsEnum::ADMIN_UPDATE->value)
      ->name("users.update");
    Route::delete("/", [UserController::class, "destroy"])
      ->middleware(["destroy-protection", "permission:" . PermissionsEnum::ADMIN_DESTROY->value])
      ->name("users.destroy");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_RESTORE->value)
      ->patch("/restore", [UserController::class, "restore"])
      ->name("users.restore");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_DUPLICATE->value)
      ->patch("/duplicate", [UserController::class, "duplicate"])
      ->name("users.duplicate");
  });
});
