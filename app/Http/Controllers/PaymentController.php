<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        try {
            $page = max((int) $request->query('page', 1), 1); // par défaut 1
            $perPage = min((int) $request->query('per_page', 20), 100); // Stripe max 100

            $options = [
                'customer' => $request->user_stripe_id,
                'type'     => 'card',
                'limit'    => $perPage,
            ];

            $paymentMethods = null;
            $startingAfter = null;

            // Stripe ne supporte pas offset -> on avance avec starting_after
            for ($i = 1; $i <= $page; $i++) {
                if ($startingAfter) {
                    $options['starting_after'] = $startingAfter;
                }

                $paymentMethods = $stripe->paymentMethods->all($options);

                if ($i < $page && $paymentMethods->has_more) {
                    $lastItem = end($paymentMethods->data);
                    $startingAfter = $lastItem ? $lastItem->id : null;

                    if (!$startingAfter) {
                        break;
                    }
                }
            }

            return response()->json([
                'data'          => $paymentMethods ? $paymentMethods->data : [],
                'current_page'  => $page,
                'per_page'      => $perPage,
                'has_more'      => $paymentMethods ? $paymentMethods->has_more : false,
                'next_page_url' => $paymentMethods && $paymentMethods->has_more
                    ? url("/api/payment-methods?page=" . ($page + 1) . "&per_page={$perPage}")
                    : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Erreur Stripe',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update la méthode de paiement associé à l'utilisateur.
     */
    public function update(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();

        if (!$user->stripe_id) {
            return response()->json(['error' => 'Utilisateur sans stripe_id'], 404);
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $stripe->paymentMethods->attach(
                $request->payment_method,
                ['customer' => $user->stripe_id]
            );

            // Définir comme méthode par défaut pour facturation
            $stripe->customers->update($user->stripe_id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method
                ]
            ]);

            return response()->json(['message' => 'Méthode de paiement mise à jour']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur Stripe',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
    {
         $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return response()->json([
                'status' => 'error',
                'message' => 'session_id manquant dans l\'URL.'
            ], 400);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = CheckoutSession::retrieve($sessionId);

            $data = [
                'session_id'       => $session->id,
                'customer_id'      => $session->customer,
                'subscription_id'  => $session->subscription ?? null,
                'invoice_id'       => $session->invoice ?? null,
                'payment_intent_id'=> $session->payment_intent ?? null,
                'amount_total'     => $session->amount_total ?? null,
                'currency'         => $session->currency ?? null,
            ];

            return response()->json([
                'status' => 'success',
                'data'   => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function cancel()
    {
        return response()->json(['message' => 'Paiement échoué.']);
    }
}
