<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Stripe\StripeClient;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockStripe();
    }

    protected function mockStripe()
    {
        $mock = $this->createMock(StripeClient::class);
        $this->app->instance(StripeClient::class, $mock);
    }


    /** @test */
    public function it_can_create_an_address()
    {
        $address = Address::create([
            'country' => 'France',
            'city' => 'Paris',
            'postal_code' => '75001',
            'streetname' => 'Rue de Rivoli',
            'number' => '10',
        ]);

        $this->assertDatabaseHas('address', [
            'city' => 'Paris',
            'postal_code' => '75001',
        ]);
    }

    /** @test */
    public function it_can_have_many_users()
    {
        $address = Address::factory()->create();

        $users = User::factory(3)->create([
            'address_id' => $address->id, 
        ]);

        $this->assertCount(3, $address->users);
        $this->assertTrue($address->users->first() instanceof User);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $address = Address::factory()->create();

        $address->delete();

        $this->assertSoftDeleted('address', [
            'id' => $address->id,
        ]);
    }
}
