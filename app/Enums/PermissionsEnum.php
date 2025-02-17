<?php

namespace App\Enums;

/**
 * A class to define the Permissions.
 *
 * Check the config/permission.php file for the roles.
 */
enum PermissionsEnum: string
{
  /**
   * Define the permissions.
   *
   * format: case NAMEINAPP = 'name-in-database';
   */
  // Admin permissions
  case ADMIN_SHOW = "admin.show";
  case ADMIN_STORE = "admin.store";
  case ADMIN_UPDATE = "admin.update";
  case ADMIN_DESTROY = "admin.destroy";
  case ADMIN_RESTORE = "admin.restore";
  case ADMIN_FORCE_DESTRUCT = "admin.force-destruct";
  case ADMIN_DUPLICATE = "admin.duplicate";

  /**
   * Get the display name of the permission.
   * extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
   *
   * @return string
   */
  public function getDisplayName(): string
  {
    return match ($this) {
      // Admin permissions
      self::ADMIN_SHOW => __("permissions.admin.show"),
      self::ADMIN_STORE => __("permissions.admin.store"),
      self::ADMIN_UPDATE => __("permissions.admin.update"),
      self::ADMIN_DESTROY => __("permissions.admin.destroy"),
      self::ADMIN_RESTORE => __("permissions.admin.restore"),
      self::ADMIN_FORCE_DESTRUCT => __("permissions.admin.force-destruct"),
      self::ADMIN_DUPLICATE => __("permissions.admin.duplicate"),
    };
  }

  /**
   * Convert a permission to a format that can be used in the code.
   *
   * @param string $permission
   * @return string
   */
  private static function formatPermission(string $permission): string
  {
    return strtoupper(str_replace([".", "-"], "_", $permission));
  }

  /**
   * Get the permissions.
   *
   * @return array
   */
  public static function getPermissions(): array
  {
    return array_merge(self::getAdminPermissions());
  }

  /**
   * Get the admin permissions.
   *
   * @return array
   */
  public static function getAdminPermissions(): array
  {
    $strStart = "admin.";
    return array_map(
      fn($permission) => constant("self::" . self::formatPermission($strStart . $permission)),
      config("permission.permissions.admin")
    );
  }
}
