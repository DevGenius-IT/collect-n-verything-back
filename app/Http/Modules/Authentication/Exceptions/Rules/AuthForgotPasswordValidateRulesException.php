<?php

namespace App\Http\Modules\Authentication\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The AuthForgotPasswordValidateRules exception class.
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
class AuthForgotPasswordValidateRulesException extends ExceptionHandler
{
  protected string $name = "AuthForgotPasswordValidateRules";

  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];

    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "email" => __("authentication.rules.email"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
