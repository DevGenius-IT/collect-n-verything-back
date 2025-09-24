<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        // Crée un utilisateur avec factory
        $user = User::factory()->create();

        // Vérifie que l'utilisateur existe en base
        $this->assertDatabaseHas('user', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function it_can_have_an_address()
    {
        $address = Address::factory()->create();
        $user = User::factory()->for($address)->create();

        // Vérifie la relation
        $this->assertInstanceOf(Address::class, $user->address);
        $this->assertEquals($address->id, $user->address->id);
    }

    /** @test */
    public function it_can_soft_delete_a_user()
    {
        $user = User::factory()->create();

        $user->delete();

        // Vérifie que le user est soft deleted
        $this->assertSoftDeleted('user', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_generate_full_name()
    {
        $user = User::factory()->make([
            'firstname' => 'Mariane',
            'lastname' => 'Aharag'
        ]);

        $this->assertEquals('Mariane Aharag', $user->fullName());
    }

    /** @test */
    public function it_can_create_admin_user()
    {
        $admin = User::factory()->admin()->create();

        $this->assertEquals(User::TYPE_ADMIN, $admin->type);
    }
}
