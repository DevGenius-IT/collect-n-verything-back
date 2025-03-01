<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * The number of super admins to create.
   *
   * @var int
   */
  private int $superAdminCount = 2;

  /**
   * The number of admins to create.
   *
   * @var int
   */
  private int $adminCount = 10;

  /**
   * The number of viewers to create.
   *
   * @var int
   */
  private int $userCount = 50;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $adminLastname = Env("ADMIN_LASTNAME");
    $adminFirstname = Env("ADMIN_FIRSTNAME");
    $adminUsername = Env("ADMIN_USERNAME");
    $adminEmail = Env("ADMIN_EMAIL");
    $adminPassword = Env("ADMIN_PASSWORD");

    if ($adminLastname && $adminFirstname && $adminUsername && $adminEmail && $adminPassword) {
      // Debugging output
      $adminDetails = [
        "Lastname" => $adminLastname,
        "Firstname" => $adminFirstname,
        "Username" => $adminUsername,
        "Email" => $adminEmail,
        "Password" => $adminPassword,
      ];

      $this->dumpSuperAdminFromConfig();

      $data = [
        "lastname" => $adminLastname,
        "firstname" => $adminFirstname,
        "username" => $adminUsername,
        "email" => $adminEmail,
        "password" => Hash::make($adminPassword),
        "phone_number" => null,
        "type" => "USER",
        "stripe_id" => null,        
        "updated_at" => now(),
        "created_at" => now(),
      ];

      $this->createUser($data, RolesEnum::SUPER_ADMIN->value);
    } else {
      echo "Admin user not created. Please ensure all required environment variables are set.\n";
      echo "Run `php artisan config:clear` to refresh the configuration cache and try again.\n";
    }

    $this->createUser(
      [
        "email" => "admin@collect-verything.com",
        "password" => Hash::make("@Admin123"),
      ],
      RolesEnum::ADMIN->value
    );

    $this->createUser(
      [
        "email" => "user@collect-verything.com",
        "password" => Hash::make("@User123"),
      ],
      RolesEnum::USER->value
    );

    // Create the users
    $this->createSuperAdmins();
    $this->createAdmins();
    $this->createUsers();
  }

  /**
   * Dump the super admin details from the configuration.
   *
   * @return void
   */
  private function dumpSuperAdminFromConfig(): void
  {
    $adminDetails = [
      "Lastname" => Env("ADMIN_LASTNAME"),
      "Firstname" => Env("ADMIN_FIRSTNAME"),
      "Username" => Env("ADMIN_USERNAME"),
      "Email" => Env("ADMIN_EMAIL"),
      "Password" => Env("ADMIN_PASSWORD"),
    ];

    echo "\nAdmin user created with the following details:\n\n";
    foreach ($adminDetails as $key => $value) {
      echo "  - $key: $value\n";
    }
  }

  /**
   * Create a user.
   *
   * @param array|null $data
   * @param null|RolesEnum|string $role
   * @return User
   */
  private function createUser(?array $data, null|RolesEnum|string $role): User
  {
    if (is_string($role)) {
      $role = RolesEnum::from($role);
    }

    $user = User::factory()
      ->create($data ?? [])
      ->assignRole(RolesEnum::USER->value);

    if ($role && $role !== RolesEnum::USER) {
      $user->assignRole($role->value);
    }

    return $user;
  }

  /**
   * Create a super admin users.
   *
   * @return void
   */
  private function createSuperAdmins(): void
  {
    for ($i = 0; $i < $this->superAdminCount; $i++) {
      $this->createUser(null, RolesEnum::SUPER_ADMIN);
    }
  }

  /**
   * Create an admin users.
   *
   * @return void
   */
  private function createAdmins(): void
  {
    for ($i = 0; $i < $this->adminCount; $i++) {
      $this->createUser(null, RolesEnum::ADMIN);
    }
  }

  /**
   * Create a viewer users.
   *
   * @return void
   */
  private function createUsers(): void
  {
    for ($i = 0; $i < $this->userCount; $i++) {
      $this->createUser(null, RolesEnum::USER);
    }
  }
}
