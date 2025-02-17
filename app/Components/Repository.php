<?php

namespace App\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
  * The repository class.
  *
  * @package App\Components
  *
  * *****Methods*******
  * @method public __construct(Model $model, Ressource $ressource)
  * @method public count(array $params): int
  * @method public index(array $params): Collection|array
  * @method public show(int $id): Model|Collection|array
  * @method public store(array $data): Model|array
  * @method public update(int $id, array $data): Model|array
  * @method public restore(array $ids): JsonResponse
  * @method public destroy(array $ids, bool $force = false): JsonResponse
  * @method public duplicate(array $data): array
  * @method protected applyFilters(array $params): QueryBuilder|string
  * @method protected isSoftDeletes(): bool
  * @method private applyFields(array $params, QueryBuilder $query): void
  * @method private applyOrder(array $params, QueryBuilder $query): void
  * @method private applyOffset(array $params, QueryBuilder $query): void
  * @method private applySoftDeletes(array $params, QueryBuilder $query): void
  * @method private setFilters(array $params, QueryBuilder $query): void
  */
class Repository
{
  /**
   * The model instance.
   *
   * @var Model
   */
  protected Model $model;

  /**
   * The ressource instance.
   *
   * @var Ressource
   */
  protected Ressource $ressource;

  public function __construct(Model $model, Ressource $ressource)
  {
    $this->model = $model;
    $this->ressource = $ressource;
  }

  /**
   * The count method.
   *
   * @param array $params
   * @return int
   * @throws ExceptionHandler
   */
  public function count(array $params): int
  {
    try {
      return $this->applyFilters($params)->count();
    } catch (ExceptionHandler) {
      throw new ExceptionHandler(__("components.repository.count_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The applyFilters method.
   *
   * @param array $params
   * @return QueryBuilder|string
   * @throws ExceptionHandler
   */
  protected function applyFilters(array $params): QueryBuilder|string
  {
    try {
      $query = QueryBuilder::for($this->model::class)->take($params["limit"] ?? 50);

      $this->applyFields($params, $query);
      $this->setFilters($params, $query);
      $this->applyOrder($params, $query);
      $this->applyOffset($params, $query);
      $this->applySoftDeletes($params, $query);

      return $query;
    } catch (ExceptionHandler) {
      throw new ExceptionHandler(__("filters.apply_filters"), null, 400);
    }
  }

  /**
   * Apply fields to the query.
   *
   * @param array $params
   * @param QueryBuilder $query
   * @return void
   */
  private function applyFields(array &$params, QueryBuilder $query): void
  {
    if (isset($params["fields"])) {
      $query->allowedFields($params["fields"]);
      unset($params["fields"]);
    }
  }

  /**
   * Apply order to the query.
   *
   * @param array $params
   * @param QueryBuilder $query
   * @return void
   */
  private function applyOrder(array &$params, QueryBuilder $query): void
  {
    if (isset($params["order"])) {
      $orderBy = $params["orderBy"] ?? "id";
      $query->defaultSort($params["order"] === "desc" ? "-" . $orderBy : $orderBy);
      unset($params["order"], $params["orderBy"]);
    }
  }

  /**
   * Apply offset to the query.
   *
   * @param array $params
   * @param QueryBuilder $query
   * @return void
   */
  private function applyOffset(array &$params, QueryBuilder $query): void
  {
    if (isset($params["offset"])) {
      $query->skip($params["offset"]);
      unset($params["offset"]);
    }
  }

  /**
   * Apply soft deletes to the query.
   *
   * @param array $params
   * @param QueryBuilder $query
   * @return void
   */
  private function applySoftDeletes(array &$params, QueryBuilder $query): void
  {
    if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model)) && isset($params["trash"])) {
        $query = match ($params["trash"]) {
          "with" => $query->withTrashed(),
          "only" => $query->onlyTrashed(),
          default => $query,
        };
      unset($params["trash"]);
    }
  }

  /**
   * Set filters parameters in applyFilters method.
   *
   * @param array $params
   * @param QueryBuilder $query
   * @return void
   */
  private function setFilters(array $params, QueryBuilder $query): void
  {
    if (isset($params["filters"])) {
      foreach ($params["filters"] as $key => $value) {
        if (is_array($value)) {
          $query->whereHas($key, function ($subQuery) use ($value) {
            $subQuery->whereIn($subQuery->getModel()->getTable() . ".id", $value);
          });
        } else {
          $query->where($key, 'like', '%' . $value . '%');
        }
      }
    }
  }

  /**
   * The isSoftDeletes method.
   *
   * @return bool
   */
  private function isSoftDeletes(): bool
  {
    return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model));
  }

  /**
   * The index method.
   *
   * @param array $params
   * @return Collection|array
   * @throws ExceptionHandler
   */
  public
  function index(array $params): Collection|array
  {
    try {
      return $this->applyFilters($params)->get();
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.index_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The show method
   *
   * @param int $id
   * @return Model|Collection|array
   * @throws ExceptionHandler
   */
  public
  function show(int $id): Model|Collection|array
  {
    try {
      return $this->model->findOrFail($id);
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.show_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The store method.
   *
   * @param array $data
   * @return Model|array
   * @throws ExceptionHandler
   */
  public
  function store(array $data): Model|array
  {
    try {
      return DB::transaction(function () use ($data) {
        return $this->model->create($data);
      });
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.store_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The update method.
   *
   * @param int $id
   * @param array $data
   * @return Model|array
   * @throws ExceptionHandler
   */
  public
  function update(int $id, array $data): Model|array
  {
    try {

      $item = $this->model->findOrFail($id);
      return DB::transaction(function () use ($item, $data) {
        $item->update($data);
        return $item;
      });
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.update_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The destroy method.
   *
   * @param array $ids
   * @param bool $force
   * @return JsonResponse
   * @throws ExceptionHandler
   */
  public function destroy(array $ids, bool $force = false): JsonResponse
  {
    try {
      DB::transaction(function () use ($ids, $force) {
        if ($force) {
          $this->model->withTrashed()->whereIn('id', $ids)->forceDelete();
        } else {
          $this->model->destroy($ids);
        }
      });
      return response()->json(["message" => __("components.repository.destroy_success", ['entity' => $this->model->getTable()])]);
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.destroy_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The restore method.
   *
   * @param array $ids
   * @return JsonResponse
   * @throws ExceptionHandler
   */
  public
  function restore(array $ids): JsonResponse
  {
    try {
      DB::transaction(function () use ($ids) {
        $this->model->onlyTrashed()->whereIn("id", $ids)->restore();
      });
      return response()->json(["message" => __("components.repository.restore_success", ['entity' => $this->model->getTable()])]);
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.restore_failed", ['entity' => $this->model->getTable()]));
    }
  }

  /**
   * The duplicate method.
   *
   * @param array $data
   * @return array
   * @throws ExceptionHandler
   */
  public
  function duplicate(array $data): array
  {
    try {
      $items = [];

      DB::transaction(function () use ($data, &$items) {
        foreach ($data as $item) {
          $original = $this->model->findOrFail($item["duplicate_from"]);
          unset($item["duplicate_from"]);
          $newItem = $original->replicate()->toArray();

          $newItem = array_merge($newItem, $item);

          $result = $this->store($newItem);

          $items[] = (new $this->ressource())->toArray($result, null);
        }
      });

      return $items;
    } catch (ExceptionHandler $e) {
      throw new ExceptionHandler(__("components.repository.duplicate_failed", ['entity' => $this->model->getTable()]));
    }
  }
}
