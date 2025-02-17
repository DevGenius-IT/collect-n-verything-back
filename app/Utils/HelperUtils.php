<?php

namespace App\Utils;

/**
 * The helper utils trait.
 *
 * @package App\Utils
 *
 * *****Traits*****
 * @use StringUtils
 *
 * *****Methods*****
 * @method void removeFieldFromModel(mixed $model, array $fields)
 */
trait HelperUtils
{
  use StringUtils;

  /**
   * Remove field of curent model to avoid loop in the response.
   *
   * @param mixed $model
   * @param array<string> $fields
   * @return void
   */
  private function removeFieldFromModel($model, array &$fields): void
  {
    try {
      $modelName = $this->convertToSnakeCase(class_basename($model));

      $fields = array_filter($fields, function ($field) use ($modelName) {
        return !str_starts_with($field, $modelName);
      });
    } catch (\Exception) {
      $exceptionClass = $this->getExceptionClassFromModel($model);
      throw new $exceptionClass(
        __($this->getExceptionTranslationKeyFromModel($model) . ".remove_failed")
      );
    }
  }
}
