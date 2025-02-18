<?php

namespace App\Http\Modules\Authentication;

/**
 * The authentication rules trait.
 *
 * @package App\Http\Modules\Authentication
 *
 * *****Properties*****
 * @property array $recoveryByEmailRules
 * @property array $resetPasswordRules
 */
trait AuthRules
{
  /**
   * The recovery by email rules for authentication.
   *
   * @var array
   */
  protected array $recoveryByEmailRules = [
    "email" => "required|email|exists:users,email",
  ];

  /**
   * The reset password rules for authentication.
   *
   * @var array
   */
  protected array $resetPasswordRules = [
    "password" => [
      "required",
      "string",
      "min:6",
      "regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/",
    ],
    "password_confirmation" => "required|string|same:password",
  ];
}
