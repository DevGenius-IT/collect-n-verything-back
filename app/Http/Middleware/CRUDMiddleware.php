<?php

namespace App\Http\Middleware;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Providers\Exceptions\PolicyException;
use App\Utils\StringUtils;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * The CRUD middleware class.
 *
 * @package App\Http\Middleware
 *
 * *****Traits*****
 * @use StringUtils
 */
class CRUDMiddleware
{
  use StringUtils;

  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   * @throws PolicyException
   */
  public function handle(Request $request, Closure $next)
  {
    $user = $request->user();

    if ($user->hasRole(RolesEnum::SUPER_ADMIN->value)) {
      return $next($request);
    }

    if ($this->isForce($request)) {
      return $next($request);
    } else {
      $request->merge(["force" => 0]);
    }

    if ($this->isShowingRoute($request)) {
      return $next($request);
    }

    if ($user->hasRole(RolesEnum::ADMIN->value)) {
      $daysDelay = Env("ADMIN_ACTION_DELAY_DAYS", 7); // Default to 7 days if not set

      $role = DB::table("model_has_roles")
        ->where("role_id", Role::where("name", RolesEnum::ADMIN->value)->first()->id)
        ->where("model_id", $user->id)
        ->first();

      if ($role) {
        $remainingDays = (int) ($daysDelay - Carbon::parse($role->created_at)->diffInDays(now()));

        if ($remainingDays <= 0) {
          return $next($request);
        } else {
          throw new PolicyException(
            __("components.crud_policy.cannot_perform_action", [
              "time" => $this->makeTimerInDays($remainingDays),
            ]),
            null,
            403
          );
        }
      }
    }

    return $next($request);
  }

  /**
   * Check if the route is showing a record.
   *
   * @param Request $request
   * @return bool
   */
  private function isShowingRoute(Request $request): bool
  {
    return str_contains($request->route()->getName(), "show") ||
      str_contains($request->route()->getName(), "index");
  }

  /**
   * Check if the request is a force delete request.
   *
   * @param Request $request
   * @return bool
   */
  private function isForce(Request $request): bool
  {
    return $request->input("force") &&
      $request->user()->hasPermissionTo(PermissionsEnum::ADMIN_FORCE_DESTRUCT->value);
  }
}
