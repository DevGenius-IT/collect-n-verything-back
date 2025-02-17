<?php

namespace App\Components;

use Illuminate\Support\Collection;

/**
 * The ressource class.
 *
 * @package App\Components
 *
 * ****Methods*******
 * @method array|Collection toArrayCollection(mixed $items, array|null $fields)
 * @method array|Collection toArray(mixed $item, array|null $fields)
 * @method array|Collection transform(mixed $item, array|null $fields)
 * @method bool isRelationRequested(array|null $fields, string $relation)
 */
class Ressource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param mixed $item
   * @param array|null $fields
   * @return array|Collection
   * @throws ExceptionHandler
   */
  public function toArrayCollection(mixed $items, array|null $fields): array|Collection
  {
    try {
      return Collection::make($items)
        ->map(function ($item) use ($fields) {
          return $this->transform($item, $fields);
        })
        ->toArray();
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.ressource.transform_failed"));
    }
  }

  /**
   * Transform the resource into an array.
   *
   * @param mixed $item
   * @param array|null $fields
   * @return array|Collection
   * @throws ExceptionHandler
   */
  public function toArray(mixed $item, array|null $fields): array|Collection
  {
    try {
      return $this->transform($item, $fields);
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.ressource.transform_failed"));
    }
  }

  /**
   * Transform the given item into a resource.
   *
   * @param mixed $item
   * @param array|null $fields
   * @return array|Collection
   * @throws ExceptionHandler
   */
  protected function transform(mixed $item, array|null $fields): array|Collection
  {
    try {
      return $item->toArray($item, $fields);
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.ressource.transform_failed"));
    }
  }

  /**
   * Check if relation is requested.
   *
   * @param array|null $fields
   * @param string $relation
   * @return bool
   */
  protected function isRelationRequested(array|null $fields, string $relation): bool
  {
    return empty($fields) || in_array($relation, $fields);
  }
}
