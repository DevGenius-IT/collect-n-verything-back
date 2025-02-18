<?php

namespace Tests\Feature;

use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
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
    $this->baseUrl = $this->getBaseUrl(true) . "/addresses";
    $this->entityStructure = [
      "id",
      "street",
      "additional",
      "locality",
      "zip_code",
      "city",
      "department",
      "country",
      "schools",
      "users",
      "workplaces",
      "created_at",
      "updated_at",
      "deleted_at",
    ];
  }

  /**
   * Test listing addresses.
   *
   * @return void
   */
  public function test_can_list_addresses()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->getJson($this->baseUrl);

    $response->assertStatus(200)->assertJsonStructure($this->indexStructure());
  }

  /**
   * Test showing a address.
   *
   * @return void
   */
  public function test_can_show_address()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->getJson($this->baseUrl . "/3");

    $response->assertStatus(200)->assertJsonStructure($this->entityStructure);
  }

  /**
   * Test creating a address.
   *
   * @return void
   */
  public function test_can_create_address()
  {
    $addressData = [
      "street" => "3 Rue de la Paix",
      "additional" => "Appartement 3",
      "locality" => "Paris",
      "zip_code" => "75000",
      "city" => "Paris",
      "department" => "Ile-de-France",
      "country" => "France",
    ];

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->postJson($this->baseUrl, $addressData);

    $response->assertStatus(201)->assertJsonStructure($this->entityStructure);
  }

  /**
   * Test updating a address.
   *
   * @return void
   */
  public function test_can_update_address()
  {
    $updateData = [
      "street" => "11 Rue Gambetta",
      "additional" => "Appartement 11",
      "locality" => "Nancy",
      "zip_code" => "54000",
      "city" => "Nancy",
      "department" => "Lorraine",
      "country" => "France",
    ];

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->putJson($this->baseUrl . "/3", $updateData);

    $response->assertStatus(200)->assertJson([
      "street" => $updateData["street"],
      "additional" => $updateData["additional"],
      "locality" => $updateData["locality"],
      "zip_code" => $updateData["zip_code"],
      "city" => $updateData["city"],
      "department" => $updateData["department"],
      "country" => $updateData["country"],
    ]);
  }

  /**
   * Test deleting a address.
   *
   * @return void
   */
  public function test_can_delete_address()
  {
    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->deleteJson($this->baseUrl, ["ids" => [3]]);

    $response->assertStatus(200)->assertJson([
      "message" => __("components.repository.destroy_success", ["entity" => "addresses"]),
    ]);
  }
  
  /**
   * Test restoring a address.
   *
   * @return void
   */
  public function test_can_restore_address()
  {
    Address::find(3)->delete();

    $response = $this->withHeaders([
      "Accept" => "application/json",
      "Authorization" => "Bearer " . $this->getToken(),
    ])->patchJson($this->baseUrl . "/restore", ["ids" => [3]]);

    $response->assertStatus(200)->assertJson([
      "message" => __("components.repository.restore_success", ["entity" => "addresses"]),
    ]);
  }

  /**
   * Test duplicating a address.
   *
   * @return void
   */
  public function test_can_duplicate_address()
  {
    $duplicateData = [
      [
        "duplicate_from" => 2,
        "street" => "10 Rue de la République",
        "additional" => "Appartement 10",
        "locality" => "Lyon",
        "zip_code" => "69000",
        "city" => "Lyon",
        "department" => "Rhône",
        "country" => "France",
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
