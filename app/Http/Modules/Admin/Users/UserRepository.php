<?php

namespace App\Http\Modules\Admin\Users;

use App\Components\ExceptionHandler;
use App\Components\Repository;
use App\Enums\RolesEnum;
use App\Helpers\SchoolsHelper;
use App\Helpers\SectionsHelper;
use App\Helpers\ShopsHelper;
use App\Helpers\UsersHelper;
use App\Helpers\UserTypesHelper;
use App\Http\Modules\Admin\Users\Exceptions\UserRepositoryException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * The user repository class.
 *
 * @package App\Http\Modules\Admin\Users
 * @extends Repository
 *
 * *****Traits*****
 * @use SchoolsHelper
 * @use SectionsHelper
 * @use ShopsHelper
 * @use UsersHelper
 * @use UserTypesHelper
 *
 ******Methods*******
 * @method public __construct(User $model, UserRessource $ressource)
 * @method public store(array $data): User|array
 * @method public update(int $id, array $data): User|array
 * @method public destroy(array $ids, bool $force = false): JsonResponse
 */
class UserRepository extends Repository
{
  use SchoolsHelper, SectionsHelper, ShopsHelper, UsersHelper, UserTypesHelper;

  public function __construct(User $model, UserRessource $ressource)
  {
    parent::__construct($model, $ressource);
  }

  /**
   * The store method.
   *
   * @param array $data
   * @return User|array
   * @throws UserRepositoryException
   */
  public function store(array $data): User|array
  {
    try {
      return DB::transaction(function () use ($data) {
        $schools = $this->extractSchools($data);
        $sections = $this->extractSections($data);
        $shops = $this->extractShops($data);
        $userTypes = $this->extractUserTypes($data);
        $this->hashPassword($data);
        $this->stringifyAgeRange($data);

        $user = $this->model->create($data);
        $this->assignRoles($user, $data);

        $this->syncUserTypes($user, $userTypes);
        $this->syncSchools($user, $schools);
        $this->syncSections($user, $sections);
        $this->syncShops($user, $shops);

        return $user;
      });
    } catch (ExceptionHandler $e) {
      throw new UserRepositoryException(__("users.store_failed"));
    }
  }

  /**
   * The update method.
   *
   * @param int $id
   * @param array $data
   * @return User|array
   * @throws UserRepositoryException
   */
  public function update(int $id, array $data): User|array
  {
    try {
      $user = $this->model->findOrFail($id);

      DB::transaction(function () use ($user, $data) {
        $this->assignRoles($user, $data);

        if (isset($data["schools"])) {
          $this->syncSchools($user, $this->extractSchools($data));
        }

        if (isset($data["sections"])) {
          $this->syncSections($user, $this->extractSections($data));
        }

        if (isset($data["shops"])) {
          $this->syncShops($user, $this->extractShops($data));
        }
        
        if (isset($data["user_types"])) {
          $this->syncUserTypes($user, $this->extractUserTypes($data));
        }

        $this->hashPassword($data);
        $this->stringifyAgeRange($data);
        
        $user->update($data);
      });
      return $user;
    } catch (ExceptionHandler $e) {
      throw new UserRepositoryException(__("users.update_failed"));
    }
  }

  /**
   * The destroy method.
   *
   * @param array $ids
   * @param bool $force
   * @return JsonResponse
   * @throws UserRepositoryException
   */
  public function destroy(array $ids, bool $force = false): JsonResponse
  {
    try {
      DB::transaction(function () use ($ids, $force) {
        $users = $force
          ? $this->model->withTrashed()->whereIn("id", $ids)->get()
          : $this->model->whereIn("id", $ids)->get();

        foreach ($users as $user) {
          if (
            $user->hasRole(RolesEnum::ADMIN->value) &&
            !auth()->guard()->user()->hasRole(RolesEnum::SUPER_ADMIN->value)
          ) {
            throw new UserRepositoryException(__("users.destroy_failed"));
          }

          if (auth()->guard()->user()->id === $user->id) {
            throw new UserRepositoryException(__("users.cannot_destroy_self"));
          }
        }

        if ($force) {
          $this->model->withTrashed()->whereIn("id", $ids)->forceDelete();
        } else {
          $this->model->whereIn("id", $ids)->delete();
        }
      });
      return response()->json(["message" => __("users.destroy_success")]);
    } catch (\Exception $e) {
      throw new UserRepositoryException(__("users.destroy_failed"));
    }
  }
}
