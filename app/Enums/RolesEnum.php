<?php

namespace App\Enums;

/**
 * A class to define the roles.
 *
 * Check the config/permission.php file for the roles.
 */
enum RolesEnum: string
{
  /**
   * Define the roles.
   *
   * format: case NAMEINAPP = 'name-in-database';
   */
  // Base roles
  case SUPER_ADMIN = "super-admin";
  case ADMIN = "admin";
  case VIEWER = "viewer";

  /**
   * Get the display name of the role.
   * extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
   *
   * @return string
   */
  public function getDisplayName(): string
  {
    return match ($this) {
      self::SUPER_ADMIN => __("roles.super-admin"),
      self::ADMIN => __("roles.admin"),
      self::VIEWER => __("roles.viewer"),
    };
  }

  /**
   * Convert a role to a format that can be used in the code.
   *
   * @param string $role
   * @return string
   */
  private static function formatRole(string $role): string
  {
    return strtoupper(str_replace("-", "_", $role));
  }

  /**
   * Get the roles.
   *
   * @return array
   */
  public static function getRoles(): array
  {
    return array_map(
      fn($role) => constant("self::" . self::formatRole($role)),
      array_merge(config("permission.roles.base"))
    );
  }

  /**
   * Get the base roles.
   *
   * @return array
   */
  public static function getBaseRoles(): array
  {
    return array_map(
      fn($role) => constant("self::" . self::formatRole($role)),
      config("permission.roles.base")
    );
  }

  /**
   * Get the admin roles.
   *
   * @return array
   */
  public static function getAdminRoles(): array
  {
    return array_map(
      fn($role) => constant("self::" . self::formatRole($role)),
      array_filter(config("permission.roles.base"), fn($role) => $role !== self::VIEWER->value)
    );
  }

  /**
   * Check if the role exists.
   *
   * @param string $role
   * @return bool
   */
  public static function hasEnum(string $role): bool
  {
    $roles = self::getRoles();
    return in_array($role, array_map(fn($r) => $r->value, $roles));
  }
}
