<?php

namespace App\Http\Modules\Authentication\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The recovery exception class.
 *
 * @package App\Http\Modules\Authentication\Exceptions
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class RecoveryException extends ExceptionHandler
{
  protected string $name = "Recovery";
  
  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
