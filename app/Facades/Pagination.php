<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The pagination facade class.
 *
 * @package App\Facades
 * @extends Facade
 *
 * *****Methods*******
 * @method static string getFacadeAccessor()
 */
class Pagination extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return "pagination";
  }
}
