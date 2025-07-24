<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Stripe\StripeClient;

class StripeController extends Controller
{

    public function products()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $products = Product::all();

        foreach ($products->data as &$product) {
            $prices = Price::all(['product' => $product->id]);
            $product->prices = $prices->data;
        }
        return response()->json($products->data);
    }

    public function createSession(Request $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $user = User::find($request->user_id);

        $checkoutData = [
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => $user->id,
            ],
            'line_items' => [[
                'price' => $request->price_id,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => env('APP_FRONTEND_URL') . '/session/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_FRONTEND_URL') . '/session/cancel',
        ];

        if ($user->stripe_id != null) {
            $checkoutData['customer'] = $user->stripe_id;
        }

        $session = $stripe->checkout->sessions->create($checkoutData);

        return response()->json(['url' => $session->url]);
    }
}
