<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  /**
   * The password to use for the tests.
   *
   * @var string
   */
  private string $password = "@User123";

  /**
   * Test user registration.
   *
   * @return void
   */
  public function test_user_can_register()
  {
    $response = $this->postJson($this->getBaseUrl() . "/auth/signup", [
      "firstname" => "John",
      "lastname" => "Doe",
      "username" => "johndoe",
      "email" => "johndoe@fichespedagogiques.com",
      "password" => "@User123",
      "password_confirmation" => "@User123",
      "advantage_number" => "",
      "roles" => [RolesEnum::USER],
    ]);

    $response->assertStatus(201)->assertJsonStructure(["token", "user"]);
  }

  /**
   * Test user login.
   *
   * @return void
   */
  public function test_user_can_login()
  {
    $user = User::factory()->create([
      "password" => Hash::make($this->password),
    ]);

    $response = $this->withHeaders([
      "Accept" => "application/json",
    ])->postJson($this->getBaseUrl() . "/auth/signin", [
      "email" => $user->email,
      "password" => $this->password,
    ]);

    $response
      ->assertStatus(200)
      ->assertJsonStructure([
        "token",
        "user" => [
          "id",
          "firstname",
          "lastname",
          "username",
          "email",
          "roles",
          "enabled",
          "password_requested_at",
          "phone_number",
          "has_newsletter",
          "has_commercials",
          "has_thematic_alerts",
          "credits",
          "credits_expire_at",
          "advantage_number",
          "created_at",
          "updated_at",
          "deleted_at",
        ],
      ]);
  }

  /**
   * Test user verify token.
   *
   * @return void
   */
  public function test_user_can_verify_token()
  {
    $user = User::factory()->create();

    $token = $user->createToken("api_token")->plainTextToken;

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $token,
    ])->get($this->getBaseUrl() . "/auth/verify");

    $response
      ->assertStatus(200)
      ->assertJsonStructure([
        "token",
        "user" => [
          "id",
          "firstname",
          "lastname",
          "username",
          "email",
          "roles",
          "enabled",
          "password_requested_at",
          "phone_number",
          "has_newsletter",
          "has_commercials",
          "has_thematic_alerts",
          "credits",
          "credits_expire_at",
          "advantage_number",
          "created_at",
          "updated_at",
          "deleted_at",
        ],
      ]);
  }

  /**
   * Test user logout.
   *
   * @return void
   */
  public function test_user_can_logout()
  {
    $user = User::factory()->create();

    $token = $user->createToken("api_token")->plainTextToken;

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $token,
    ])->get($this->getBaseUrl() . "/auth/signout");

    $response->assertStatus(200)->assertJson(["message" => __("authentication.sign_out")]);
  }

  /**
   * Test user forgot password by email.
   *
   * @return void
   */
  public function test_user_can_forgot_password()
  {
    $user = User::factory()->create();

    $response = $this->withHeaders(["Accept" => "application/json"])->postJson(
      $this->getBaseUrl() . "/auth/forgot-password/email",
      [
        "identifier" => $user->email,
      ]
    );

    $response->assertStatus(200)->assertJson(["message" => __("authentication.recovery_sent")]);
  }

  /**
   * Test user can reset password.
   *
   * @return void
   */
  public function test_user_can_reset_password()
  {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $user
      ->forceFill([
        "reset_password_token" => $token,
        "password_requested_at" => now()->subMinute(),
      ])
      ->save();

    $response = $this->withHeaders([
      "Accept" => "application/json",
    ])->postJson($this->getBaseUrl() . "/auth/reset-password/" . $token, [
      "password" => $this->password,
      "password_confirmation" => $this->password,
    ]);

    $response->assertStatus(200)->assertJson(["message" => __("authentication.password_reset")]);
  }
}
