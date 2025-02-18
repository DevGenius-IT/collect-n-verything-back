<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The AddressDestroyOrRestoreValidateRules exception.
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
class AddressDestroyOrRestoreValidateRulesException extends ExceptionHandler
{
  protected string $name = "AddressDestroyOrRestoreValidateRules";

  protected function setErrors(array|string $errors): void
  {
    $keys = array_keys($errors);
    $formattedErrors = [];

    foreach ($keys as $key) {
      $formattedErrors[$key] = match ($key) {
        "ids" => __("filters.ids"),
        "force" => __("filters.force"),
        default => __("filters.invalid_field"),
      };
    }

    $this->errors = $formattedErrors;
  }
}
