<?php

namespace App\Http\Modules\Authentication\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The AuthResetPasswordValidateRules exception.
 *
 * @package App\Http\Modules\Authentication\Exceptions\Rules
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class AuthResetPasswordValidateRulesException extends ExceptionHandler
{
  protected string $name = "AuthResetPasswordValidateRules";

  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];

    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "password" => __("authentication.rules.password"),
        "password_confirmation" => __("authentication.rules.password_confirmation"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
