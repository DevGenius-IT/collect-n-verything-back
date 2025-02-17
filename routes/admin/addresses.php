<?php

use App\Enums\PermissionsEnum;
use Illuminate\Support\Facades\Route;

use App\Http\Modules\Admin\Addresses\AddressController;

// Addresses endpoints
Route::middleware("auth:sanctum")->group(function () {
  Route::prefix("addresses")->group(function () {
    Route::middleware("permission:" . PermissionsEnum::ADMIN_SHOW->value)->group(function () {
      Route::get("/", [AddressController::class, "index"])->name("addresses.index");
      Route::get("/{address}", [AddressController::class, "show"])->name("addresses.show");
    });
    Route::middleware("permission:" . PermissionsEnum::ADMIN_STORE->value)
      ->post("/", [AddressController::class, "store"])
      ->name("addresses.store");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_UPDATE->value)
      ->put("/{address}", [AddressController::class, "update"])
      ->name("addresses.update");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_DESTROY->value)
      ->delete("/", [AddressController::class, "destroy"])
      ->name("addresses.destroy");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_RESTORE->value)
      ->patch("/restore", [AddressController::class, "restore"])
      ->name("addresses.restore");
    Route::middleware("permission:" . PermissionsEnum::ADMIN_DUPLICATE->value)
      ->patch("/duplicate", [AddressController::class, "duplicate"])
      ->name("addresses.duplicate");
  });
});
