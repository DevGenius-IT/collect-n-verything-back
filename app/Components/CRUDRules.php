<?php

namespace App\Components;

use App\Helpers\RulesHelper;
use Illuminate\Support\Facades\Validator;

/**
 * The CRUD rules class.
 *
 * @package App\Components
 * @extends Rules
 *
 * *****Traits*****
 * @use RulesHelper
 *
 * *****Properties*****
 * @property string $name
 * @property array $primitiveRules
 * @property array $rules
 * @property string $orderByRules
 * @property array $dataCollectionRules
 * @property bool $hasCustomRules
 *
 * *****Methods*******
 * @method void __construct(string $name)
 * @method array index(array $params, ExceptionHandler $exception)
 * @method array store(array $params, ExceptionHandler $exception)
 * @method array update(array $params, ExceptionHandler $exception)
 * @method array destroy(array $params, ExceptionHandler $exception)
 * @method array restore(array $params, ExceptionHandler $exception)
 * @method array duplicate(array $params, ExceptionHandler $exception)
 */
class CRUDRules extends Rules
{
  use RulesHelper;

  /**
   * The name of current data.
   *
   * @var string
   */
  protected string $name;

  /**
   * The primitive rules for the current data.
   *
   * @var array
   */
  protected array $primitiveRules;

  /**
   * The rules for the current data.
   *
   * @var array
   */
  protected array $rules;

  /**
   * The rules for the order_by parameter.
   *
   * @var string
   */
  protected string $orderByRules;

  /**
   * The rules for the current data collection.
   *
   * @var array
   */
  protected array $dataCollectionRules;

  /**
   * The current rules use custom rules.
   *
   * @var array<string>
   */
  protected array $customRules = ["file"];

  /**
   * Create a new instance.
   *
   * @param  string  $name
   */
  public function __construct(string $name)
  {
    $this->name = $name;
    if (in_array($name, $this->customRules)) {
      $this->primitiveRules = $this->{"get" . ucfirst($this->name) . "PrimitiveRules"}();
      $this->orderByRules = $this->{$this->name . "OrderByRules"};
      $this->rules = $this->{"get" . ucfirst($this->name) . "Rules"}();
      $this->dataCollectionRules = $this->{"get" . ucfirst($this->name) . "DataCollectionRules"}();
    } else {
      $this->primitiveRules = $this->{$this->name . "PrimitiveRules"};
      $this->orderByRules = $this->{$this->name . "OrderByRules"};
      $this->rules = $this->{$this->name . "Rules"};
      $this->dataCollectionRules = $this->{$this->name . "DataCollectionRules"};
      $this->duplicateRules = array_merge($this->duplicateRules, $this->dataCollectionRules);
    }
  }

  /**
   * Validate parameters for the index method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function index(array $params, ExceptionHandler $exception): array
  {
    $rules = array_merge(
      $this->indexRules,
      ["orderBy" => $this->orderByRules],
      $this->primitiveRules
    );

    $validator = Validator::make($params, $rules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }

  /**
   * Validate parameters for the store method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function store(array $params, ExceptionHandler $exception): array
  {
    $validator = Validator::make($params, $this->rules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }

  /**
   * Validate parameters for the update method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function update(array $params, ExceptionHandler $exception): array
  {
    $validator = Validator::make($params, $this->primitiveRules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }

  /**
   * Validate parameters for the destroy method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function destroy(array $params, ExceptionHandler $exception): array
  {
    $validator = Validator::make($params, $this->idsRules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }

  /**
   * Validate parameters for the restore method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function restore(array $params, ExceptionHandler $exception): array
  {
    $validator = Validator::make($params, $this->idsRules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }

  /**
   * Validate parameters for the duplicate method.
   *
   * @param  array  $params
   * @param  ExceptionHandler  $exception
   * @return array
   * @throws ExceptionHandler
   */
  public function duplicate(array $params, ExceptionHandler $exception): array
  {
    $validator = Validator::make($params, $this->duplicateRules);

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      throw new $exception($errors);
    }

    return $validator->validated();
  }
}
