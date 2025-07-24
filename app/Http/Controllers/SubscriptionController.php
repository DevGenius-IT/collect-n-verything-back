<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->stripe_id) {
            return response()->json(['error' => 'Utilisateur non lié à Stripe.'], 404);
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $subscriptions = $stripe->subscriptions->all([
                'customer' => $user->stripe_id,
                'status' => 'all',
            ]);

            return response()->json([
                'subscriptions' => $subscriptions->data,
                'user_stripe_id' => $user->stripe_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des abonnements Stripe.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $subscriptionId = $request->subscription_id;
            $canceled = $stripe->subscriptions->cancel($subscriptionId, []);
            return response()->json([
                'message' => 'Abonnement annulé',
                'subscription' => $canceled,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'annulation de l\'abonnement ' . $request->subscription_id,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string',
            'new_price_id' => 'required|string',
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $subscription = $stripe->subscriptions->retrieve($request->subscription_id);

            $updated = $stripe->subscriptions->update($request->subscription_id, [
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $request->new_price_id,
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]);

            return response()->json([
                'message' => 'Abonnement mis à jour',
                'subscription' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
