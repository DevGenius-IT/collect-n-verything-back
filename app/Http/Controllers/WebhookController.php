<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            Log::error("Webhook signature error: " . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $stripe = new StripeClient(config('services.stripe.secret'));

            Log::info("Session : " . $session);
            $userId = $session->metadata->user_id ?? null;
            $stripeCustomerId = $session->customer ?? null;

            if (!$userId) {
                return response()->json(['error' => 'user_id manquant dans metadata'], 400);
            }

            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non trouvé'], 404);
            }

            if (!$user->stripe_id) {
                $user->stripe_id = $stripeCustomerId;
                $user->save();
            }
        }

        return response('Webhook reçu', 200);
    }
}
