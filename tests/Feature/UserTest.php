<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Entity structure.
   *
   * @var array
   */
  protected array $entityStructure;

  /**
   * Base URL.
   *
   * @var string
   */
  protected string $baseUrl;

  protected function setUp(): void
  {
    parent::setUp();
    $this->baseUrl = $this->getBaseUrl(true) . "/users";
    $this->entityStructure = [
      "id",
      "lastname",
      "firstname",
      "username",
      "email",
      "enabled",
      "roles",
      "permissions",
      "password_requested_at",
      "phone_number",
      "has_newsletter",
      "address",
      "oAuth",
      "created_at",
      "updated_at",
      "deleted_at",
    ];
  }

  /**
   * Test listing users.
   *
   * @return void
   */
  public function test_can_list_users()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->getJson($this->baseUrl);

    $response->assertStatus(200)->assertJsonStructure($this->indexStructure());
    $this->assertDatabaseCount("users", $response["meta"]["total"]);
  }

  /**
   * Test showing a user.
   *
   * @return void
   */
  public function test_can_show_user()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->getJson($this->baseUrl . "/2");

    $response->assertStatus(200)->assertJsonStructure($this->entityStructure);
  }

  /**
   * Test creating a user.
   *
   * @return void
   */
  public function test_can_create_user()
  {
    $userData = [
      "lastname" => "Doe",
      "firstname" => "John",
      "username" => "john_doe",
      "email" => "johndoe@fichespedagogiques.com",
      "roles" => [RolesEnum::VIEWER->value],
      "password" => "@User123",
      "phone_number" => "+1234567890",
      "has_newsletter" => true,
      "address_id" => 9,
    ];

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->postJson($this->baseUrl, $userData);

    $response->assertStatus(201)->assertJsonStructure($this->entityStructure);
  }

  /**
   * Test updating a user.
   *
   * @return void
   */
  public function test_can_update_user()
  {
    $updateData = [
      "lastname" => "Smith",
      "firstname" => "Jane",
      "username" => "jane_smith",
      "email" => "janesmith@test.com",
      "roles" => [RolesEnum::ADMIN->value],
      "password" => "@Test123",
      "phone_number" => "+0987654321",
      "has_newsletter" => true,
      "address_id" => 10,
    ];

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->putJson($this->baseUrl . "/2", $updateData);

    $response->assertStatus(200)->assertJson([
      "lastname" => $updateData["lastname"],
      "firstname" => $updateData["firstname"],
      "username" => $updateData["username"],
      "email" => $updateData["email"],
      "phone_number" => $updateData["phone_number"],
      "has_newsletter" => $updateData["has_newsletter"],
    ]);
  }

  /**
   * Test deleting a user.
   *
   * @return void
   */
  public function test_can_delete_user()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->deleteJson($this->baseUrl, ["ids" => ["2"]]);

    $response->assertStatus(200)->assertJson([
      "message" => __("users.destroy_success"),
    ]);
  }

  /**
   * Test restoring a user.
   *
   * @return void
   */
  public function test_can_restore_user()
  {
    User::find(2)->delete();

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->patchJson($this->baseUrl . "/restore", ["ids" => [2]]);

    $response->assertStatus(200)->assertJson([
      "message" => __("components.repository.restore_success", ["entity" => "users"]),
    ]);
  }

  /**
   * Test duplicating a user.
   *
   * @return void
   */
  public function test_can_duplicate_user()
  {
    $duplicateData = [
      [
        "duplicate_from" => 2,
        "lastname" => "Doe",
        "firstname" => "John",
        "username" => "john_doe2",
        "email" => "johndoe2@fichespedagogiques.com",
        "password" => "@User123",
        "address_id" => 9,
      ],
    ];

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->patchJson($this->baseUrl . "/duplicate", $duplicateData);

    $response->assertStatus(201)->assertJsonStructure([
      "*" => $this->entityStructure,
    ]);
  }
}
