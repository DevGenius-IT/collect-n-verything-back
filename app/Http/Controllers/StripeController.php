<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;

class StripeController extends Controller
{
    // Retourne un client_secret pour Stripe.js
    public function createSetupIntent(Request $request)
    {
        $intent = $request->user()->createSetupIntent();

        return response()->json([
            'client_secret' => $intent->client_secret,
        ]);
    }

    /**
     * List of all the existing products on stripe.
     */
    public function listProductsWithPrices()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Récupère les produits actifs
        $products = Product::all(['active' => true]);

        $result = [];

        foreach ($products->data as $product) {
            // Récupère les prix associés au produit
            $prices = Price::all(['product' => $product->id]);

            $result[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'prices' => collect($prices->data)->map(function ($price) {
                    return [
                        'price_id' => $price->id,
                        'unit_amount' => $price->unit_amount / 100,
                        'currency' => $price->currency,
                        'interval' => $price->recurring->interval ?? null,
                    ];
                }),
            ];
        }

        return response()->json($result);
    }
}
