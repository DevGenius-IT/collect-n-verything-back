<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccesTokenMiddlewareTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test cannot access admin routes without token.
   *
   * @return void
   */
  public function test_cannot_access_admin_routes_without_token()
  {
    $response = $this->getJson($this->getBaseUrl(true) . "/users");
    $response->assertStatus(401);
  }

  /**
   * Test cannot access admin routes with user role.
   *
   * @return void
   */
  public function test_cannot_access_admin_routes_with_viewer_role()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getUserToken(),
    ])->getJson($this->getBaseUrl(true) . "/users");
    
    $response->assertStatus(403);
  }

  /**
   * Test cannot access admin routes with user role.
   *
   * @return void
   */
  public function test_can_access_admin_routes_with_admin_role()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->getJson($this->getBaseUrl(true) . "/users");
    
    $response->assertStatus(200);
  }
}
