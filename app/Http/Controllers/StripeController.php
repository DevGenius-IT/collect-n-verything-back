<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;

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
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = $request->user();

        $session = Session::create([
            'customer_email' => $user->email,
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
        ]);

        return response()->json(['url' => $session->url]);
    }
}
