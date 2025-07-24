<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

include base_path("routes/auth.php");

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("api")->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('addresses', AddressController::class);
        // Route::apiResource('packs', PackController::class);
        // Route::apiResource('subscriptions', SubscriptionController::class);
        Route::apiResource('websites', WebsiteController::class);
        Route::apiResource('questions', QuestionController::class);
        Route::apiResource('answers', AnswerController::class);

        // Route::prefix("subscriptions")->group(function () {
        //     Route::post('/checkout/session', [SubscriptionController::class, 'createCheckoutSession']);
        //     Route::post('/checkout/success', [SubscriptionController::class, 'successSubscription']);
        //     Route::post('/checkout/cancel', [SubscriptionController::class, 'cancelSubscription']);
        //     Route::get('/check', [SubscriptionController::class, 'checkSubscription']);
        // });

        // Route::prefix("stripe")->group(function () {
        //     Route::get('/setup-intent', [StripeController::class, 'setup-intent']);
        //     Route::post('/products', [StripeController::class, 'listProductsWithPrices']);
        //     Route::post('/webhook', [StripeWebhookController::class, 'handle']);
        // });

        Route::get('/products', [StripeController::class, 'products']);

        // Stripe Checkout
        Route::post('/checkout/session', [StripeController::class, 'createSession']);

        // Abonnement
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::get('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
        Route::get('/subscriptions/change', [SubscriptionController::class, 'changePlan']);

        // Payment Methods
        Route::get('/payment-methods', [PaymentController::class, 'index']);
        Route::get('/payment-methods/success', [PaymentController::class, 'success']);
        Route::get('/payment-methods/cancel', [PaymentController::class, 'cancel']);
        Route::post('/payment-methods/update', [PaymentController::class, 'update']);

        // Factures
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::get('/invoices/{id}', [InvoiceController::class, 'details']);
        Route::get('/invoices/{id}/download', [InvoiceController::class, 'download']);

        // Stats
        Route::get('/statistics', [StatsController::class, 'index']);
    });
});

Route::post('/api/webhook/stripe', [WebhookController::class, 'handle']);
