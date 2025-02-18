<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Utils\StringUtils;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CRUDMiddlewareTest extends TestCase
{
  use RefreshDatabase, StringUtils;

  /**
   * Base URL.
   *
   * @var string
   */
  protected string $baseUrl;

  protected function setUp(): void
  {
    parent::setUp();
    $this->baseUrl = $this->getBaseUrl(true, "/users");
  }

  /**
   * Test super admin has immediate access.
   *
   * @return void
   */
  public function test_super_admin_has_immediate_access()
  {
    $response = $this->withHeaders([
      "Authorization" => "Bearer " . $this->getToken(),
    ])->get($this->baseUrl);

    $response->assertStatus(200);
  }

  /**
   * Test basic user cannot access admin routes.
   *
   * @return void
   */
  public function test_basic_user_cannot_access_admin_routes()
  {
    $response = $this->withHeaders([
      "Authorization" => "Bearer " . $this->getUserToken(),
    ])->get($this->baseUrl);

    $response->assertStatus(403);
  }

  /**
   * Test new admin is restricted by delay.
   *
   * @return void
   */
  public function test_new_admin_is_restricted_by_delay()
  {
    $admin = User::factory()->create();
    $adminRole = Role::where("name", RolesEnum::ADMIN->value)->first();
    $admin->assignRole($adminRole);

    $response = $this->withHeaders([
      "Authorization" => "Bearer " . $admin->createToken("test")->plainTextToken,
    ])->put($this->baseUrl . "/1");

    $response->assertStatus(403);
    $response->assertJsonFragment([
      "errors" => __("components.crud_policy.cannot_perform_action", [
        "time" => $this->makeTimerInDays(Env("ADMIN_ACTION_DELAY_DAYS", 7)),
      ]),
    ]);
  }

  /**
   * Test seasoned admin has full access.
   *
   * @return void
   */
  public function test_seasoned_admin_has_full_access()
  {
    $admin = $this->createSeasonedAdmin();

    $response = $this->withHeaders([
      "Authorization" => "Bearer " . $admin->createToken("test")->plainTextToken,
    ])->get($this->baseUrl);

    $response->assertStatus(200);
  }

  /**
   * Create a seasoned admin.
   *
   * @return User
   */
  private function createSeasonedAdmin(): User
  {
    $admin = User::factory()->create();
    $admin->assignRole(RolesEnum::ADMIN->value);
    DB::table("model_has_roles")
      ->where("role_id", Role::where("name", RolesEnum::ADMIN->value)->first()->id)
      ->where("model_id", $admin->id)
      ->update([
        "created_at" => Carbon::now()->subDays(Env("ADMIN_ACTION_DELAY_DAYS", 7) + 1),
      ]);

    return $admin;
  }
}
