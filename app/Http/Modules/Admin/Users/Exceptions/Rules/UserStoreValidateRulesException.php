<?php

namespace App\Http\Modules\Admin\Users\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The user store validate rules exception.
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
class UserStoreValidateRulesException extends ExceptionHandler
{
  protected string $name = "UserStoreValidateRules";

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
        "enabled" => __("users.rules.enabled"),
        "has_newsletter" => __("users.rules.has_newsletter"),
        "has_commercials" => __("users.rules.has_commercials"),
        "has_thematic_alerts" => __("users.rules.has_thematic_alerts"),
        "advantage_number" => __("users.rules.advantage_number"),
        "age_range" => __("users.rules.age_range"),
        "age_range.*" => __("users.rules.age_range"),
        "address_id" => __("users.rules.address_id"),
        "workplace_id" => __("users.rules.workplace_id"),
        "roles" => __("users.rules.roles"),
        "roles.*" => __("users.rules.roles_item"),
        "sections" => __("users.rules.sections"),
        "sections.*" => __("users.rules.sections_item"),
        "shops" => __("users.rules.shops"),
        "shops.*" => __("users.rules.shops_item"),
        "schools" => __("users.rules.schools"),
        "schools.*" => __("users.rules.schools_item"),
        "user_types" => __("users.rules.user_types"),
        "user_types.*" => __("users.rules.user_types_item"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
