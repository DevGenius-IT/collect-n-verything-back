<?php

namespace App\Http\Modules\Admin\Users\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The user policy exception.
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
class UserRepositoryException extends ExceptionHandler
{
  protected string $name = "UserRepository";

  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
