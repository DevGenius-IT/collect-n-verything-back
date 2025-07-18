<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Validators\SubscriptionValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends CrudController
{
    protected string $modelClass = Subscription::class;
    protected string $modelName = 'Abonnement';

    protected $service;

    public function __construct(SubscriptionService $service, SubscriptionValidator $validator)
    {
        parent::__construct($service, $validator);
    }


    public function subscribe(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'price_id' => 'required|string',
            'subscription_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation échouée',
                'messages' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $paymentMethod = $request->payment_method;
            $priceId = $request->price_id;
            $subscriptionName = $request->subscription_name ?? 'default';

            $subscription = $this->service->subscribe(
                $user,
                $paymentMethod,
                $priceId,
                $subscriptionName
            );

            return response()->json([
                'message' => 'Abonnement activé avec succès',
                'subscription' => $subscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la souscription',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function checkSubscription(Request $request)
    {
        return response()->json([
            'subscribed' => $request->user()->subscribed('default'),
        ]);
    }
}
