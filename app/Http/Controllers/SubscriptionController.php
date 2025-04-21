<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Validators\SubscriptionValidator;

class SubscriptionController extends CrudController
{
    protected string $modelClass = Subscription::class;
    protected string $modelName = 'Abonnement';

    protected $service;

    public function __construct(SubscriptionService $service, SubscriptionValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
