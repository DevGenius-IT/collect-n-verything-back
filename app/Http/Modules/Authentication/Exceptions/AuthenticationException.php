<?php

namespace App\Http\Modules\Authentication\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The authentication exception class.
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
class AuthenticationException extends ExceptionHandler
{
  protected string $name = "Authentication";
  
  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
