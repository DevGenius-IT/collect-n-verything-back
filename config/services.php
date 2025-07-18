<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  "postmark" => [
    "token" => env("POSTMARK_TOKEN"),
  ],

  'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
  ],

  "ses" => [
    "key" => env("AWS_ACCESS_KEY_ID"),
    "secret" => env("AWS_SECRET_ACCESS_KEY"),
    "region" => env("AWS_DEFAULT_REGION", "us-east-1"),
  ],

  "resend" => [
    "key" => env("RESEND_KEY"),
  ],

  "slack" => [
    "notifications" => [
      "bot_user_oauth_token" => env("SLACK_BOT_USER_OAUTH_TOKEN"),
      "channel" => env("SLACK_BOT_USER_DEFAULT_CHANNEL"),
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Recovery Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for recovery services such
    | as Email, SMS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  // Recovery Methods
  "recovery" => explode(',', env("RECOVERY_METHODS", "email")),

  /*
    |--------------------------------------------------------------------------
    | OAuth Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for OAuth services such
    | as Google, Facebook, Twitter and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  // OAuth Providers
  "oauth" => explode(',', env("OAUTH_PROVIDERS", "google")),

  // Google OAuth Provider
  "google" => [
    "client_id" => env("GOOGLE_CLIENT_ID"),
    "client_secret" => env("GOOGLE_CLIENT_SECRET"),
    "redirect" => env("GOOGLE_REDIRECT_URI"),
  ],

  // Facebook OAuth Provider
  "facebook" => [
    "client_id" => env("FACEBOOK_CLIENT_ID"),
    "client_secret" => env("FACEBOOK_CLIENT_SECRET"),
    "redirect" => env("FACEBOOK_REDIRECT_URI"),
  ],
];
