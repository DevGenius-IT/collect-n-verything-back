<?php

namespace App\Helpers;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * The roles helper trait.
 *
 * @package App\Helpers
 *
 * *****Methods*****
 * @method array getRolesNamesFromUser(User $user)
 */
trait RolesHelper
{
  /**
   * Get the roles keys combined with their names.
   *
   * @param User $user
   * @return array
   */
  private function getRolesNamesFromUser(User $user): array
  {
    $getRoleNames = function ($relation) {
      return DB::table("roles")->where("id", $relation->role_id)->value("name");
    };

    $getModelRoles = function ($modelId, $modelType) use ($getRoleNames) {
      return DB::table("model_has_roles")
        ->where("model_id", $modelId)
        ->where("model_type", $modelType)
        ->get()
        ->map($getRoleNames)
        ->toArray();
    };

    $web = $getModelRoles($user->id, "App\Models\User");

    return array_combine(
      array_merge($web),
      array_map(function ($role) {
        return RolesEnum::from($role)->getDisplayName();
      }, array_merge($web))
    );
  }
}