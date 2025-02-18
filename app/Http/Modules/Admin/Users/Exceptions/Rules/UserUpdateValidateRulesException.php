<?php

namespace App\Http\Modules\Admin\Users\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The user update validate rules exception.
 *
 * @package App\Http\Modules\Admin\Users\Exceptions\Rules
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class UserUpdateValidateRulesException extends ExceptionHandler
{
  protected string $name = "UserUpdateValidateRules";
  
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
        "phone_number" => __("users.rules.phone_number"),
        "enabled" => __("users.rules.enabled"),
        "has_newsletter" => __("users.rules.has_newsletter"),
        "address_id" => __("users.rules.address_id"),
        "roles" => __("users.rules.roles"),
        "roles.*" => __("users.rules.roles_item"),
        default => __("filters.invalid_field"),
      };
    }
    
    $this->errors = $formattedErrors;
  }
}
