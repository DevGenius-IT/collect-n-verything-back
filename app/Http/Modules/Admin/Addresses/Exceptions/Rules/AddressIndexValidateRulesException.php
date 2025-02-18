<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The AddressIndexValidateRulesException class.
 *
 * @package App\Http\Modules\Admin\Addresses\Exceptions\Rules
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class AddressIndexValidateRulesException extends ExceptionHandler
{
  protected string $name = "AddressIndexValidateRules";

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
        "street" => __("addresses.rules.street"),
        "additional" => __("addresses.rules.additional"),
        "locality" => __("addresses.rules.locality"),
        "zip_code" => __("addresses.rules.zip_code"),
        "city" => __("addresses.rules.city"),
        "department" => __("addresses.rules.department"),
        "country" => __("addresses.rules.country"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
