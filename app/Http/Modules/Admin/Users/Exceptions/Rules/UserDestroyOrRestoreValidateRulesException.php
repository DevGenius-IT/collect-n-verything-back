<?php

namespace App\Http\Modules\Admin\Users\Exceptions\Rules;

use App\Components\ExceptionHandler;

/**
 * The UserDestroyOrRestoreValidateRules exception.
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
class UserDestroyOrRestoreValidateRulesException extends ExceptionHandler
{
  protected string $name = "UserDestroyOrRestoreValidateRules";

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
