<?php

namespace App\Http\Modules\Admin\Users\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The user resource exception.
 *
 * @package App\Http\Modules\Admin\Users\Exceptions
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class UserRessourceException extends ExceptionHandler
{
  protected string $name = "UserResource";
  
  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
