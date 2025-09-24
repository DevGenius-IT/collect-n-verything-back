<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  /**
   * Indicates whether the default seeder should run before each test.
   *
   * @var bool
   */
  protected bool $seed = true;

  /**
   * Pagination Structure.
   *
   * @var array
   */
  protected array $paginationMetaStructure = [
    "total",
    "pages_count",
    "current_page",
    "limit",
    "pages",
    "selected_fields",
  ];

  /**
   * Entity structure.
   *
   * @var array
   */
  protected array $entityStructure;

  /**
   * Get the super admin user.
   *
   * @return User
   */
  protected function getAdminUser(): User
  {
    return User::where("username", ENV("ADMIN_USERNAME", "admin"))->first();
  }

  /**
   * Authenticate as an admin user.
   *
   * @return void
   */
  protected function authenticateAsAdmin(): void
  {
    $adminUser = User::where("email", Env("ADMIN_EMAIL", "super-admin@flippad.com"))->first();
    $this->actingAs($adminUser);
  }

  /**
   * Authenticate as a regular user.
   *
   * @return void
   */
  protected function authenticateAsUser(): void
  {
    $user = User::where("email", "user@flippad.com")->first();
    $this->actingAs($user);
  }

  /**
   * Get the base URL.
   *
   * @param  bool  $admin
   * @param  string  $path
   * @return string
   */
  protected function getBaseUrl(bool $admin = false, string $path = ""): string
  {
    return "/" . Env("API_VERSION") . ($admin ? "/admin" : "") . $path;
  }

  /**
   * Index structure.
   *
   * @return array
   */
  protected function indexStructure(): array
  {
    return [
      "items" => [
        "*" => $this->entityStructure,
      ],
      "meta" => $this->paginationMetaStructure,
    ];
  }
}