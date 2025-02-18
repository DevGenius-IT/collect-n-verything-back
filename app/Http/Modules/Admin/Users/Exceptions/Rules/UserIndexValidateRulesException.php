<?php

namespace App\Http\Modules\Admin\Users\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The user index validate rules exception.
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
class UserIndexValidateRulesException extends ExceptionHandler
{
  protected string $name = "UserIndexValidateRules";
  
  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];
    
    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "page" => __("filters.page"),
        "limit" => __("filters.limit"),
        "order_by" => __("filters.order_by"),
        "order" => __("filters.order"),
        "trash" => __("filters.trash"),
        "orderBy" => __("users.rules.orderBy"),
        "lastname" => __("users.rules.lastname"),
        "firstname" => __("users.rules.firstname"),
        "username" => __("users.rules.username"),
        "email" => __("users.rules.email"),
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
        "roles.*" => __("users.rules.roles"),
        "sections" => __("users.rules.sections"),
        "sections.*" => __("users.rules.sections"),
        "shops" => __("users.rules.shops"),
        "shops.*" => __("users.rules.shops"),
        "schools" => __("users.rules.schools"),
        "schools.*" => __("users.rules.schools"),
        "user_types" => __("users.rules.user_types"),
        "user_types.*" => __("users.rules.user_types"),
        default => __("filters.invalid_field"),
      };
    }
    
    $this->errors = $formattedErrors;
  }
}
