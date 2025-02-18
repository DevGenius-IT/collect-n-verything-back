<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The address duplicate validate rules exception.
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
class AddressDuplicateValidateRulesException extends ExceptionHandler
{
  protected string $name = "AddressDuplicateValidateRules";

  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];

    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "*.street" => __("addresses.rules.street"),
        "*.additional" => __("addresses.rules.additional"),
        "*.locality" => __("addresses.rules.locality"),
        "*.zip_code" => __("addresses.rules.zip_code"),
        "*.city" => __("addresses.rules.city"),
        "*.department" => __("addresses.rules.department"),
        "*.country" => __("addresses.rules.country"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
