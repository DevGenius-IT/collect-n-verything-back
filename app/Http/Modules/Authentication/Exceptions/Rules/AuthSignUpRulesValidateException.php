<?php

namespace App\Http\Modules\Authentication\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The AuthSignUpRulesValidateException class.
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
class AuthSignUpRulesValidateException extends ExceptionHandler
{
  protected string $name = "AuthSignUpRulesException";
  
  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];
    
    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "lastname" => __("users.rules.lastname"),
        "firstname" => __("users.rules.firstname"),
        "username" => __("users.rules.username"),
        "email" => __("users.rules.email"),
        "password" => __("users.rules.password"),
        "phone_number" => __("users.rules.phone_number"),
        "has_newsletter" => __("users.rules.has_newsletter"),
        "has_commercials" => __("users.rules.has_commercials"),
        "has_thematic_alerts" => __("users.rules.has_thematic_alerts"),
        "advantage_number" => __("users.rules.advantage_number"),
        "roles" => __("users.rules.roles"),
        "roles.*" => __("users.rules.roles_item"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
