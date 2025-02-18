<?php

namespace App\Http\Middleware;

use App\Enums\RolesEnum;
use App\Http\Modules\Admin\Users\Exceptions\UserPolicyException;
use Closure;
use Illuminate\Http\Request;

/**
 * The destroy protection middleware.
 *
 * @package App\Http\Middleware
 */
class DestroyProtectionMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   * @throws UserPolicyException
   */
  public function handle(Request $request, Closure $next)
  {
    $user = $request->user();

    if ($request->route()->getName() === "users.destroy") {
      $user = $request->user();
      $ids = $request->input("ids");

      foreach ($ids as $id) {
        if ($user->id === $id) {
          throw new UserPolicyException(__("users.cannot_destroy_self"), null, 403);
        }
      }
    }

    if (!$user->hasRole(RolesEnum::SUPER_ADMIN->value) && $request->input("force") === 1) {
      throw new UserPolicyException(__("middleware.cannot_destroy"), null, 403);
    }

    return $next($request);
  }
}
