<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

include base_path("routes/auth.php");

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("api")->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('websites', WebsiteController::class);
        Route::apiResource('questions', QuestionController::class);
        Route::apiResource('answers', AnswerController::class);

        Route::get('contacts', [ContactController::class, 'index']);
        Route::get('contacts/{id}', [ContactController::class, 'show']);
        Route::put('contacts/{id}', [ContactController::class, 'update']);
        Route::delete('contacts/{id}', [ContactController::class, 'destroy']);

        // Stripe Checkout
        Route::post('/checkout/session', [StripeController::class, 'createSession']);

        // Abonnement
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::get('/subscriptions/{id}', [SubscriptionController::class, 'getSubscription']);
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

Route::post('/api/contacts', [ContactController::class, 'store']);
Route::post('/api/webhook/stripe', [WebhookController::class, 'handle']);
Route::get('/api/products', [StripeController::class, 'products']);
