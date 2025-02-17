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

class DestroyProtectionMiddlewareTest extends TestCase
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
   * Test admin can destroy self.
   *
   * @return void
   */
  public function test_cannot_destroy_self()
  {
    $admin = $this->createSeasonedAdmin();
    
    $response = $this->withHeaders([
      "Authorization" => "Bearer " . $admin->createToken("admin")->plainTextToken,
    ])->delete($this->baseUrl, [
      "ids" => [$admin->id],
    ]);

    $response->assertStatus(403);
    $response->assertJsonFragment([
      "errors" => __("users.cannot_destroy_self"),
    ]);
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
