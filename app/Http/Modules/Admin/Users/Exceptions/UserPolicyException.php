<?php

namespace App\Http\Modules\Admin\Users\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The user policy exception.
 *
 * @package App\Http\Modules\Admin\Users\Exceptions
 * @extends ExceptionHandler
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 *
 * *****Methods*******
 * @method void setErrors(array|string $errors)
 */
class UserPolicyException extends ExceptionHandler
{
  protected string $name = "UserPolicy";

  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }
}
