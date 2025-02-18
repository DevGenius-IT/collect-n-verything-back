<?php

namespace App\Http\Modules\Admin\Users;

/**
 * The user rules trait.
 *
 * @package App\Http\Modules\Admin\Users
 *
 * ******Properties******
 * @property array $userPrimitiveRules
 * @property array $userRules
 * @property string $userOrderByRules
 * @property array $userDataCollectionRules
 */
trait UserRules
{
  /**
   * The primitive rules for the user data.
   *
   * @var array
   */
  protected $userPrimitiveRules = [
    "lastname" => "string",
    "firstname" => "string",
    "username" => "string",
    "email" => "string",
    "phone_number" => "string",
    "enabled" => "boolean",
    "has_newsletter" => "boolean",
    "address_id" => "integer|exists:addresses,id",
    "roles" => "array",
    "roles.*" => "string|exists:roles,name",
    "fields" => "array",
  ];

  /**
   * The rules for the user data.
   *
   * @var array
   */
  protected $userRules = [
    "lastname" => "string",
    "firstname" => "string",
    "username" => "required|string|unique:users,username",
    "email" => "required|email|unique:users,email",
    "password" => [
      "required",
      "string",
      "min:6",
      "regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/",
    ],
    "phone_number" => ["nullable", "string", "regex:/^\+?[1-9]\d{1,14}$/"],
    "enabled" => "nullable|boolean",
    "has_newsletter" => "nullable|boolean",
    "address_id" => "nullable|integer|exists:addresses,id",
    "roles" => "array|min:1",
    "roles.*" => "string|exists:roles,name",
    "fields" => "nullable|array",
  ];

  /**
   * The rules for the order_by parameter.
   *
   * @var string
   */
  protected $userOrderByRules = "nullable|string|in:lastname,firstname,username,email,phone_number,has_newsletter,address_id,enabled";

  /**
   * The rules for the user data collection.
   *
   * @var array
   */
  protected $userDataCollectionRules = [
    "*" => "required|array|min:1",
    "*.lastname" => "string",
    "*.firstname" => "string",
    "*.username" => "required|string|unique:users,username",
    "*.email" => "required|email|unique:users,email",
    "*.password" => [
      "required",
      "string",
      "min:6",
      "regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/",
    ],
    "*.phone_number" => ["nullable", "string", "regex:/^\+?[1-9]\d{1,14}$/"],
    "*.enabled" => "nullable|boolean",
    "*.has_newsletter" => "nullable|boolean",
    "*.address_id" => "nullable|integer|exists:addresses,id",
    "*.roles" => "array|min:1",
    "*.roles.*" => "string|exists:roles,name",
    "fields" => "nullable|array",
  ];
}
