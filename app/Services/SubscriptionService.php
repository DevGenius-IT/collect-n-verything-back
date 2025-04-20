<?php

namespace App\Services;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;

class SubscriptionService
{
    protected $repo;

    public function __construct(SubscriptionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(Subscription $subscription, array $data)
    {
        return $this->repo->update($subscription, $data);
    }

    public function delete(Subscription $subscription)
    {
        return $this->repo->delete($subscription);
    }
}
