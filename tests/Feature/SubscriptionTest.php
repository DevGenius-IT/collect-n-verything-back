<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_subscription()
    {
        $subscription = Subscription::factory()->create();

        $this->assertDatabaseHas('subscription', [
            'id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'type' => $subscription->type,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->for($user)->create();

        $this->assertEquals($user->id, $subscription->user->id);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $subscription = Subscription::factory()->create();
        $subscription->delete();

        $this->assertSoftDeleted('subscription', [
            'id' => $subscription->id,
        ]);
    }
}
