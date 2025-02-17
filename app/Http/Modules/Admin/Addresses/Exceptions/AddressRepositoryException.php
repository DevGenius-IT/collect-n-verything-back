<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The address repository exception.
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
class AddressRepositoryException extends ExceptionHandler
{
  protected string $name = "AddressRepository";

  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
