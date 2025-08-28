<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Stripe\StripeClient;

class StripeController extends Controller
{

    public function products(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $products = Product::all();

        try {
            // Récupération des paramètres
            $page = max((int) $request->query('page', 1), 1); // page >= 1
            $perPage = min((int) $request->query('per_page', 20), 100); // Stripe max 100

            $options = [
                'limit' => $perPage,
            ];

            $products = null;
            $startingAfter = null;

            for ($i = 1; $i <= $page; $i++) {
                if ($startingAfter) {
                    $options['starting_after'] = $startingAfter;
                }

                $products = Product::all($options);

                if ($i < $page && $products->has_more) {
                    $lastItem = end($products->data);
                    $startingAfter = $lastItem ? $lastItem->id : null;

                    if (!$startingAfter) {
                        break;
                    }
                }
            }

            if ($products) {
                foreach ($products->data as &$product) {
                    $prices = Price::all(['product' => $product->id]);
                    $product->prices = $prices->data;
                }
            }

            return response()->json([
                'data'          => $products ? $products->data : [],
                'current_page'  => $page,
                'per_page'      => $perPage,
                'has_more'      => $products ? $products->has_more : false,
                'next_page_url' => $products && $products->has_more
                    ? url("/api/products?page=" . ($page + 1) . "&per_page={$perPage}")
                    : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la récupération des produits Stripe.',
                'message' => $e->getMessage()
            ], 500);
        }
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
            'success_url' => env('APP_FRONTEND_URL') . '/api/payment-methods/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => env('APP_FRONTEND_URL') . '/api/payment-methods/cancel',
        ];

        if ($user->stripe_id != null) {
            $checkoutData['customer'] = $user->stripe_id;
        }

        $session = $stripe->checkout->sessions->create($checkoutData);

        return response()->json(['url' => $session->url]);
    }
}
