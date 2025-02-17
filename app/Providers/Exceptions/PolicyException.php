<?php

namespace App\Providers\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The policy exception class.
 *
 * @package App\Providers\Exceptions
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 */
class PolicyException extends ExceptionHandler
{
  protected string $name = "Policy";
}
