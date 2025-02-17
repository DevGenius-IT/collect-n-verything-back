<?php

namespace App\Components;

/**
 * The rules class.
 *
 * @package App\Components
 *
 * *****Properties*****
 * @property array $indexRules
 * @property array $idsRules
 * @property array $duplicateRules
 *
 * *****Methods*******
 * @method array addManyRules(array &$rules, array $rulesToAdd)
 * @method array addRule(array &$rules, string $name, string|array $value)
 * @method array deleteRule(array &$rules, string $name)
 */
class Rules
{
  /**
   * The rules for the index method.
   *
   * @var array
   */
  protected array $indexRules = [
    "page" => "integer|nullable|min:1",
    "limit" => "integer|nullable|min:1",
    "order_by" => "string|nullable",
    "order" => "string|nullable|in:asc,desc",
    "trash" => "string|nullable|in:only,with",
  ];

  /**
   * The rules for the ids data
   *
   * @var array
   */
  protected array $idsRules = [
    "ids" => "required|array|min:1",
    "force" => "boolean|nullable",
  ];

  /**
   * The rules for the duplicate method.
   *
   * @var array
   */
  protected array $duplicateRules = [
    "*.duplicate_from" => "required|integer",
  ];

  /**
   * Add many rules to the rules array.
   *
   * @param array $rules
   * @param array $rulesToAdd
   * @return array
   */
  protected function addManyRules(array &$rules, array $rulesToAdd): array
  {
    foreach ($rulesToAdd as $name => $value) {
      $rules = $this->addRule($rules, $name, $value);
    }

    return $rules;
  }

  /**
   * Add a rule to the rules array.
   *
   * @param array $rules
   * @param string $name
   * @param string|array $value
   */
  protected function addRule(array &$rules, string $name, string|array $value): array
  {
    $ruleString = is_array($value) ? implode("|", $value) : $value;
    $rules[$name] = isset($rules[$name]) ? $rules[$name] . "|" . $ruleString : $ruleString;

    return $rules;
  }

  /**
   * Delete a rule from the rules array.
   *
   * @param array $rules
   * @param string $name
   */
  protected function deleteRule(array &$rules, string $name): array
  {
    unset($rules[$name]);

    return $rules;
  }
}
