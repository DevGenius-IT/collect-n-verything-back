<?php

namespace App\Helpers;

use App\Http\Modules\Admin\Addresses\Exceptions\AddressRessourceException;
use App\Http\Modules\Admin\Users\Exceptions\UserRessourceException;
use App\Models\Address;
use App\Models\User;

/**
 * The exceptions helper trait.
 *
 * @package App\Helpers
 *
 * *****Methods*****
 * @method string getExceptionClassFromModel(mixed $model)
 * @method string getExceptionTranslationKeyFromModel(mixed $model)
 */
trait ExceptionsHelper
{
  /**
   * Get the exception class based on the model.
   *
   * @param mixed $model
   * @return string
   */
  private function getExceptionClassFromModel($model): string
  {
    return match (get_class($model)) {
      Address::class => AddressRessourceException::class,
      User::class => UserRessourceException::class,
      default => \Exception::class,
    };
  }

  /**
   * Get the exception translation key based on the model.
   *
   * @param mixed $model
   * @return string
   */
  private function getExceptionTranslationKeyFromModel($model): string
  {
    return match (get_class($model)) {
      Address::class => "addresses",
      User::class => "users",
      default => "components",
    };
  }
}
