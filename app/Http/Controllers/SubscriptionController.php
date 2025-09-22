<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Checkout\Session as CheckoutSession;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->stripe_id) {
            return response()->json(['error' => 'Utilisateur non lié à Stripe.'], 404);
        }

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        try {
            $page = max((int) $request->get('page', 1), 1); 
            $perPage = min((int) $request->get('limit', 20), 100); 

            $options = [
                'customer' => $user->stripe_id,
                'status'   => 'all',
                'limit'    => $perPage,
            ];

            $subscriptions = null;
            $startingAfter = null;

            for ($i = 1; $i <= $page; $i++) {
                if ($startingAfter) {
                    $options['starting_after'] = $startingAfter;
                }

                $subscriptions = $stripe->subscriptions->all($options);

                if ($i < $page && $subscriptions->has_more) {
                    $lastItem = end($subscriptions->data);
                    $startingAfter = $lastItem ? $lastItem->id : null;

                    if (!$startingAfter) {
                        break; // plus d’items
                    }
                }
            }

            return response()->json([
                'subscriptions'   => $subscriptions ? $subscriptions->data : [],
                'has_more'        => $subscriptions ? $subscriptions->has_more : false,
                'user_stripe_id'  => $user->stripe_id,
                'current_page'    => $page,
                'limit'        => $perPage,
                'next_page_url'   => $subscriptions && $subscriptions->has_more
                    ? url("/api/subscriptions?page=" . ($page + 1) . "&limit={$perPage}")
                    : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la récupération des abonnements Stripe.',
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

    public function getLastSubscription()
    {
      $user = Auth::user();

        if (!$user || !$user->stripe_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stripe ID manquant.'
            ], 404);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $subscriptions = Subscription::all([
                'customer' => $user->stripe_id,
                'limit' => 1, 
            ]);

            $lastSubscription = $subscriptions->data[0] ?? null;

            if (!$lastSubscription) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun abonnement trouvé pour cet utilisateur.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'last_subscription' => $lastSubscription
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
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
