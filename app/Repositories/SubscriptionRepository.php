<?php

namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function all()
    {
        return Subscription::all();
    }

    public function find($id)
    {
        return Subscription::findOrFail($id);
    }

    public function create(array $data)
    {
        return Subscription::create($data);
    }

    public function update(Subscription $subscription, array $data)
    {
        $subscription->update($data);
        return $subscription;
    }

    public function delete(Subscription $subscription)
    {
        return $subscription->delete();
    }
}
