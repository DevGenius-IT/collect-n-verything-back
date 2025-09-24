<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Website;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_website()
    {
        $website = Website::factory()->create();

        $this->assertDatabaseHas('website', [
            'id' => $website->id,
            'domain' => $website->domain,
            'user_id' => $website->user_id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $website = Website::factory()->for($user)->create();

        $this->assertEquals($user->id, $website->user->id);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $website = Website::factory()->create();
        $website->delete();

        $this->assertSoftDeleted('website', [
            'id' => $website->id,
        ]);
    }
}
