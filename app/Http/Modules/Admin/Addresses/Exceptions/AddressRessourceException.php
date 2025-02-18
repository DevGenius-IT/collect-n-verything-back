<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The address resource exception.
 *
 * @package App\Http\Modules\Admin\Addresses\Exceptions
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class AddressRessourceException extends ExceptionHandler
{
  protected string $name = "AddressResource";

  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
