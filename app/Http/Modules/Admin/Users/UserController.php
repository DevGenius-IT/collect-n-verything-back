<?php

namespace App\Http\Modules\Admin\Users;

use App\Components\CRUDController;
use App\Components\ExceptionHandler;
use App\Enums\RolesEnum;
use App\Http\Modules\Admin\Users\Exceptions\Rules\UserDestroyOrRestoreValidateRulesException;
use App\Http\Modules\Admin\Users\Exceptions\Rules\UserDuplicateValidateRulesException;
use App\Http\Modules\Admin\Users\Exceptions\Rules\UserIndexValidateRulesException;
use App\Http\Modules\Admin\Users\Exceptions\Rules\UserStoreValidateRulesException;
use App\Http\Modules\Admin\Users\Exceptions\Rules\UserUpdateValidateRulesException;
use App\Http\Modules\Admin\Users\Exceptions\UserPolicyException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The user controller class.
 *
 * @package App\Http\Modules\Admin\Users
 * @extends CRUDController
 *
 * *****Methods*******
 * @method void __construct(UserRepository $repository, UserRessource $ressource)
 * @method JsonResponse index(Request $request, ExceptionHandler $_)
 * @method JsonResponse store(Request $request, ExceptionHandler $_)
 * @method JsonResponse update(int $id, Request $request, ExceptionHandler $_)
 * @method void cannotChangeRole(Request $request, int $id)
 * @method JsonResponse destroy(Request $request, ExceptionHandler $_)
 * @method JsonResponse restore(Request $request, ExceptionHandler $_)
 * @method JsonResponse duplicate(Request $request, ExceptionHandler $_)
 */
class UserController extends CRUDController
{
  public function __construct(UserRepository $repository, UserRessource $ressource)
  {
    parent::__construct("user", $repository, $ressource);
  }

  public function index(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new UserIndexValidateRulesException();
    return parent::index($request, $exception);
  }

  public function store(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new UserStoreValidateRulesException();
    return parent::store($request, $exception);
  }

  public function update(int $id, Request $request, ExceptionHandler $_): JsonResponse
  {
    $this->cannotChangeRole($request, $id);
    $exception = new UserUpdateValidateRulesException();
    return parent::update($id, $request, $exception);
  }

  /**
   * The method that checks if the user is trying to change the role of a super-admin.
   *
   * @param Request $request
   * @param int $id
   * @return void
   * @throws UserPolicyException
   */
  private function cannotChangeRole(Request $request, int $id): void
  {
    if ($request->has("roles")) {
      $userById = User::findOrFail($id);
      if (
        $request->user()->hasRole(RolesEnum::ADMIN->value) &&
        $userById->hasRole(RolesEnum::SUPER_ADMIN->value)
      ) {
        throw new UserPolicyException(__("users.cannot_change_role"), null, 403);
      }
    }
  }

  public function destroy(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new UserDestroyOrRestoreValidateRulesException();
    return parent::destroy($request, $exception);
  }

  public function restore(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new UserDestroyOrRestoreValidateRulesException();
    return parent::restore($request, $exception);
  }

  public function duplicate(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new UserDuplicateValidateRulesException();
    return parent::duplicate($request, $exception);
  }
}
