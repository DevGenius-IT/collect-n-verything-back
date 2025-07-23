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
        Log::info("🎯 Webhook Stripe reçu");
        Log::info("salut");
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            Log::error("Webhook signature error: " . $e->getMessage());
            return response('Invalid signature', 400);
        }


        // $payload = $request->getContent();
        // $event = json_decode($payload);
        if ($event->type === 'checkout.session.completed') {
            Log::info("bjr");
            $session = $event->data->object;
            $stripe = new StripeClient(config('services.stripe.secret'));
            Log::info("Session ID : " . $session->id);
            Log::info("Session : " . $session);
            $subscription = $stripe->subscriptions->retrieve($session->subscription);

            $userId = $session->metadata->user_id ?? null;
            $stripeCustomerId = $session->customer ?? null;

            Log::info("USER :" + $stripeCustomerId);
            if (!$userId) {
                return response()->json(['error' => 'user_id manquant dans metadata'], 400);
            }
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non trouvé'], 404);
            }

            if (!$user->stripe_id) {
                // $customer = $stripe->customers->create([
                //     'email' => "salut",
                //     'name' => $user->name ?? null,
                //     'metadata' => [
                //         'user_id' => $user->id,
                //     ],
                // ]);

                // Enregistre stripe_id en DB
                $user->stripe_id = $stripeCustomerId;
                $user->save();
            }

            if ($user) {
                Subscription::updateOrCreate(
                    ['stripe_subscription_id' => $subscription->id],
                    [
                        'user_id' => $user->id,
                        'price_id' => $subscription->items->data[0]->price->id,
                        'status' => $subscription->status,
                        'starts_at' => Carbon::createFromTimestamp($subscription->current_period_start),
                        'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end),
                    ]
                );
            }
        }


        return response('Webhook reçu', 200);
    }
}
