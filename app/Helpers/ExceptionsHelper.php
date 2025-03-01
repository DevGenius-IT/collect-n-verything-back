<?php

namespace App\Helpers;
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
   * Get the exception translation key based on the model.
   *
   * @param mixed $model
   * @return string
   */
  private function getExceptionTranslationKeyFromModel($model): string
  {
    return match (get_class($model)) {
      User::class => "users",
      default => "components",
    };
  }
}
