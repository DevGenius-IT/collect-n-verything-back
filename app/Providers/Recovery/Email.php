<?php

namespace App\Providers\Recovery;

use App\Http\Modules\Authentication\AuthRules;
use App\Http\Modules\Authentication\Exceptions\RecoveryException;
use App\Http\Modules\Authentication\Exceptions\Rules\AuthForgotPasswordValidateRulesException;
use App\Http\Modules\Authentication\Mail\ResetPasswordMail;
use App\Models\User;
use App\Utils\StringUtils;
use App\Utils\UrlUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

trait Email
{
  use UrlUtils, StringUtils, AuthRules;

  /**
   * Send the recovery token to the user.
   *
   * @param string $email
   * @return JsonResponse
   * @throws AuthForgotPasswordValidateRulesException
   * @throws RecoveryException
   */
  public function sendTokenByEmail(string $email): JsonResponse
  {
    try {
      $validator = Validator::make(["email" => $email], $this->recoveryByEmailRules);

      if ($validator->fails()) {
        throw new AuthForgotPasswordValidateRulesException(
          $validator->errors()->toArray(),
          null,
          400
        );
      }

      $user = User::where("email", $email)->firstOrFail();

      if (!$user) {
        throw new RecoveryException(__("authentication.invalid_credentials"), null, 400);
      }

      $this->checkUserPasswordRequest($user);

      $token = Password::createToken($user);

      $user
        ->forceFill([
          "reset_password_token" => $token,
          "password_requested_at" => now(),
        ])
        ->save();

      $resetPasswordUrl = $this->resetPasswordUrl($token, $user->email);

      $this->sendEmail($user, $resetPasswordUrl);

      return response()->json(["message" => __("authentication.recovery_sent")]);
    } catch (RecoveryException $e) {
      if (isset($user)) {
        $this->cancelRecovery($user);
      }

      throw new RecoveryException(
        $e->getErrors() ?? __("authentication.failed_recovery"),
        null,
        400
      );
    }
  }

  /**
   * Check if user is authorized to reset password and calculate remaining time.
   *
   * @param User $user
   * @throws RecoveryException
   */
  public function checkUserPasswordRequest(User $user): void
  {
    if (!$user->password_requested_at) {
      return;
    }

    $expirationTime = Env("PASSWORD_EXPIRATION_TIME", 10);

    $elapsedTime = $user->password_requested_at->diffInMinutes(now());
    $remainingTime = $expirationTime - $elapsedTime;

    if ($remainingTime <= 0) {
      return;
    }

    throw new RecoveryException(
      __("authentication.reset_password_waits", [
        "time" => $this->makeTimerInMinutes($remainingTime),
      ]),
      null,
      400
    );
  }

  /**
   * Send the recovery email to the user.
   *
   * @param User $user
   * @param string $resetPasswordUrl
   * @return void
   * @throws RecoveryException
   */
  public function sendEmail(User $user, string $resetPasswordUrl): void
  {
    try {
      Mail::to($user)->queue(new ResetPasswordMail($user, $resetPasswordUrl));
    } catch (RecoveryException $e) {
      throw new RecoveryException(__("authentication.failed_to_send_recovery"), null, 400);
    }
  }

  /**
   * Cancel the password recovery.
   *
   * @param User $user
   * @return void
   * @throws RecoveryException
   */
  public function cancelRecovery(User $user): void
  {
    try {
      $user
        ->forceFill([
          "reset_password_token" => null,
          "password_requested_at" => null,
        ])
        ->save();
    } catch (RecoveryException $e) {
      throw new RecoveryException(__("authentication.failed_to_reset_password"), null, 400);
    }
  }
}
