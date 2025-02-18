<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
  /**
   * The base roles.
   *
   * @var array<RolesEnum>
   */
  private array $baseRoles;

  /**
   * The admin roles.
   *
   * @var array<RolesEnum>
   */
  private array $adminRoles;

  /**
   * The admin permissions.
   *
   * @var array<PermissionsEnum>
   */
  private array $adminPermissions;

  /**
   * Create a new seeder instance.
   */
  public function __construct()
  {
    // Set the roles
    $this->baseRoles = RolesEnum::getBaseRoles();
    $this->adminRoles = RolesEnum::getAdminRoles();
    // Set the permissions
    $this->adminPermissions = PermissionsEnum::getAdminPermissions();
  }

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    app()[PermissionRegistrar::class]->forgetCachedPermissions(); // Reset cached permissions
    $this->createRoles();
    $this->createPermissions();
    $this->assignPermissionsToRoles();
    $this->dumpRoles();
  }

  /**
   * Dump roles with assigned permissions.
   */
  private function dumpRoles(): void
  {
    $roles = Role::all();

    foreach ($roles as $role) {
      if ($role->permissions->isEmpty()) {
        echo $role->name . ": no permissions\n";
        echo "\n";
        continue;
      }

      echo $role->name . ":\n";
      foreach ($role->permissions as $permission) {
        echo "  - " . $permission->name . "\n";
      }
      echo "\n";
    }
  }

  /**
   * Get guard name.
   *
   * @param RolesEnum|PermissionsEnum|string $role
   * @return string
   */
  private function getGuardName(RolesEnum|PermissionsEnum|string $value): string
  {
    if (is_string($value)) {
      $value = RolesEnum::tryFrom($value) ?? PermissionsEnum::from($value);
    }

    return "web";
  }

  /**
   * Create the roles.
   */
  private function createRoles(): void
  {
    $roles = array_merge($this->baseRoles);

    foreach ($roles as $role) {
      Role::create([
        "name" => $role->value,
        "guard_name" => $this->getGuardName($role),
      ]);
    }
  }

  /**
   * Create the permissions.
   */
  private function createPermissions(): void
  {
    $permissions = array_merge(
      $this->adminPermissions,
    );

    foreach ($permissions as $permission) {
      Permission::create([
        "name" => $permission,
        "guard_name" => $this->getGuardName($permission),
      ]);
    }
  }

  /**
   * Assigning permissions to roles.
   */
  private function assignPermissionsToRoles(): void
  {
    $this->assignPermissionsToAdminRoles();
  }

  /**
   * Assign permissions to admin roles.
   */
  private function assignPermissionsToAdminRoles(): void
  {
    $adminRoles = Role::whereIn("name", $this->adminRoles)->get();

    foreach ($adminRoles as $role) {
      $permissions =
        $role->name === RolesEnum::SUPER_ADMIN->value
          ? $this->adminPermissions
          : array_filter(
            $this->adminPermissions,
            fn($permission) => !str_contains($permission->value, "force")
          );

      $role->givePermissionTo($permissions);
    }
  }
}