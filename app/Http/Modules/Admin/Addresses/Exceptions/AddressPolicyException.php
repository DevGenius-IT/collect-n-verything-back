<?php

namespace App\Http\Modules\Admin\Addresses\Exceptions;

use App\Components\ExceptionHandler;

/**
 * The address policy exception.
 *
 * @package App\Http\Modules\Admin\Addresses\Exceptions
 * @extends ExceptionHandler
 *
 * *****Properties*****
 * @property string $name
 */
class AddressPolicyException extends ExceptionHandler
{
  protected string $name = "AddressPolicy";
}
