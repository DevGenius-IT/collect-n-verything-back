<?php

namespace App\Http\Modules\Admin\Addresses;

/**
 * The address rules trait.
 *
 * @package App\Http\Modules\Admin\Addresses
 *
 * ******Properties******
 * @property array addressPrimitiveRules
 * @property array addressRules
 * @property string addressOrderByRules
 * @property array addressDataCollectionRules
 */
trait AddressRules
{
  /**
   * The primitive rules for the address data.
   *
   * @var array
   */
  protected $addressPrimitiveRules = [
    "street" => "string",
    "additional" => "string",
    "locality" => "string",
    "zip_code" => "string",
    "city" => "string",
    "department" => "string",
    "country" => "string",
    "fields" => "array",
  ];

  /**
   * The rules for the address data.
   *
   * @var array
   */
  protected $addressRules = [
    "street" => "required|string|max:255",
    "additional" => "nullable|string|max:255",
    "locality" => "nullable|string|max:255",
    "zip_code" => "required|string|max:255",
    "city" => "required|string|max:255",
    "department" => "nullable|string|max:255",
    "country" => "required|string|max:255",
    "fields" => "nullable|array",
  ];

  /**
   * The rules for the order_by parameter.
   *
   * @var string
   */
  protected $addressOrderByRules = "nullable|string|in:id,street,additional,locality,zip_code,city,department,country";

  /**
   * The rules for the address data collection.
   *
   * @var array
   */
  protected $addressDataCollectionRules = [
    "*" => "required|array|min:1",
    "*.street" => "required|string|max:255",
    "*.additional" => "nullable|string|max:255",
    "*.locality" => "nullable|string|max:255",
    "*.zip_code" => "required|string|max:255",
    "*.city" => "required|string|max:255",
    "*.department" => "nullable|string|max:255",
    "*.country" => "required|string|max:255",
    "fields" => "nullable|array",
  ];
}
