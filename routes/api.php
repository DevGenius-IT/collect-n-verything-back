<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

include base_path("routes/auth.php");

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("api")->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('addresses', AddressController::class);
        Route::apiResource('packs', PackController::class);
        Route::apiResource('subscriptions', SubscriptionController::class);
        Route::apiResource('websites', WebsiteController::class);
        Route::apiResource('questions', QuestionController::class);
        Route::apiResource('answers', AnswerController::class);

        Route::prefix("subscriptions")->group(function () {
            Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
            Route::get('/check', [SubscriptionController::class, 'checkSubscription']);
        });

        Route::prefix("stripe")->group(function () {
            Route::get('/setup-intent', [StripeController::class, 'setup-intent']);
            Route::post('/products', [StripeController::class, 'listProductsWithPrices']);
        });
    });
});
