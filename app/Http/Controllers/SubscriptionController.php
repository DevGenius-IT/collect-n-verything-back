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
        $subscription = $request->user()->subscription('default');
        if ($subscription) {
            $subscription->cancel();
        }
        return response()->json(['message' => 'Abonnement annulé']);
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'price_id' => 'required|string',
        ]);

        $subscription = $request->user()->subscription('default');
        if ($subscription) {
            $subscription->swap($request->price_id);
        }

        return response()->json(['message' => 'Abonnement mis à jour']);
    }
}
