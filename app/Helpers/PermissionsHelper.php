<?php

namespace App\Helpers;

use App\Enums\PermissionsEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * The permissions helper trait.
 *
 * @package App\Helpers
 *
 * *****Methods*****
 * @method array getPermissionsNamesFromUser(User $user)
 */
trait PermissionsHelper
{
  /**
   * Get the permissions keys combined with their names.
   *
   * @param User $user
   * @return array
   */
  private function getPermissionsNamesFromUser(User $user): array
  {
    $getPermissionNames = function ($relation) {
      return DB::table("permissions")->where("id", $relation->permission_id)->value("name");
    };

    $getRolePermissions = function ($roleId) use ($getPermissionNames) {
      return DB::table("role_has_permissions")
        ->where("role_id", $roleId)
        ->get()
        ->map($getPermissionNames)
        ->toArray();
    };

    $getModelPermissions = function ($modelId, $modelType) use ($getRolePermissions) {
      return DB::table("model_has_roles")
        ->where("model_id", $modelId)
        ->where("model_type", $modelType)
        ->get()
        ->map(function ($relation) use ($getRolePermissions) {
          return $getRolePermissions($relation->role_id);
        })
        ->flatten()
        ->toArray();
    };

    $web = $getModelPermissions($user->id, "App\Models\User");

    return array_combine(
      array_merge($web),
      array_map(function ($permission) {
        return PermissionsEnum::from($permission)->getDisplayName();
      }, array_merge($web))
    );
  }
}