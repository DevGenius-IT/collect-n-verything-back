<?php

namespace App\Helpers;

use App\Enums\RolesEnum;
use App\Http\Modules\Admin\Users\UserRessource;
use App\Models\User;
use App\Utils\HelperUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * The users helper trait.
 *
 * @package App\Helpers
 *
 * ******Traits******
 * @use ExceptionsHelper
 * @use HelperUtils
 * *****Properties*****
 * @property array $userSingleRelatedModels
 *
 * *****Methods*****
 * @method array extractUsers(array &$data)
 * @method void syncUsers(mixed $model, array $users)
 * @method Collection|null getUsersFromModel(mixed $model)
 */
trait UsersHelper
{
  use ExceptionsHelper, HelperUtils;

  /**
   * The models that have a single user.
   *
   * @var array
   */
  private array $userSingleRelatedModels = [];

  /**
   * Extract the users from the data.
   *
   * @param array $data
   * @return array
   */
  private function extractUsers(array &$data): array
  {
    if (!isset($data["users"])) {
      return [];
    }

    $users = $data["users"];
    unset($data["users"]);

    return $users;
  }

  /**
   * Sync the users of the given model.
   *
   * @param mixed $model
   * @param array $users
   * @return void
   * @throws \Exception
   */
  private function syncUsers($model, array $users): void
  {
    $model->users()->detach();
    foreach ($users as $user) {
      $user = User::where("id", $user)->first();
      if ($user) {
        $model->users()->attach($user);
      }
    }
  }

  /**
   * Get the users of the given model.
   *
   * @param mixed $model
   * @return Collection|null
   * @throws \Exception
   */
  private function getUsersFromModel($model): Collection|null
  {
    try {
      $fields = [
        "id",
        "fistname",
        "lastname",
        "username",
        "email",
        "created_at",
        "phone_number",
        "enabled",
        "has_newsletter",
        "address",
        "created_at",
        "updated_at",
        "deleted_at",
      ];

      $this->removeFieldFromModel($model, $fields);

      if (in_array(get_class($model), $this->userSingleRelatedModels)) {
        if ($model->user === null) {
          return null;
        }
        return (new UserRessource())->transform($model->user, $fields);
      }

      return $model->users->map(function ($user) use ($fields) {
        return (new UserRessource())->transform($user, $fields);
      });
    } catch (\Exception) {
      $exceptionClass = $this->getExceptionClassFromModel($model);
      throw new $exceptionClass(
        __($this->getExceptionTranslationKeyFromModel($model) . ".transform_failed")
      );
    }
  }

  /**
   * Hash the password of the given data.
   *
   * @param array $data
   * @return array
   */
  private function hashPassword(array &$data): array
  {
    if (isset($data["password"])) {
      $data["password"] = Hash::make($data["password"]);
    }
    return $data;
  }

  /**
   * Assign the roles to the given user.
   *
   * @param User $user
   * @param array $data
   * @return void
   */
  private function assignRoles(User $user, array $data): void
  {
    if (isset($data["roles"])) {
      foreach ($data["roles"] as $role) {
        if (RolesEnum::hasEnum($role)) {
          $user->assignRole($role);
        }
      }
    }
    $user->assignRole(RolesEnum::VIEWER->value);
  }

  /**
   * Stringify the age range.
   *
   * @param array $data
   * @return array
   */
  private function stringifyAgeRange(array &$data): array
  {
    if (isset($data["age_range"])) {
      $data["age_range"] = implode("-", $data["age_range"]); // [6, 12] => "6-12"
    }
    return $data;
  }

  /**
   * Transform the age range.
   *
   * @param string $ageRange
   * @return array<int>
   */
  private function transformAgeRange(string $ageRange): array
  {
    return array_map(function ($age) {
      return (int) $age;
    }, explode("-", $ageRange)); // "6-12" => [6, 12]
  }
}
