<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        try {
            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $request->user_stripe_id,
                'type' => 'card',
            ]);

            return response()->json($paymentMethods->data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur Stripe',
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

    public function success()
    {
        return response()->json(['message' => 'Paiement réussi. Le user a été associé à son abonnement.']);
    }

    public function cancel()
    {
        return response()->json(['message' => 'Paiement échoué.']);
    }
}
