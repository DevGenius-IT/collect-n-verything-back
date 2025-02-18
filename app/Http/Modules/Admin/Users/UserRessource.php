<?php

namespace App\Http\Modules\Admin\Users;

use App\Components\Ressource;
use App\Helpers\AddressesHelper;
use App\Helpers\OAuthHelper;
use App\Helpers\PermissionsHelper;
use App\Helpers\RolesHelper;
use App\Http\Modules\Admin\Users\Exceptions\UserRessourceException;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * The user ressource class.
 *
 * @package App\Http\Modules\Admin\Users
 * @extends Ressource
 *
 * *****Traits*****
 * @use AddressesHelper
 * @use PermissionsHelper
 * @use RolesHelper
 * @use OAuthHelper
 *
 * *****Methods*****
 * @method Collection transform(mixed $item, array|null $fields)
 */
class UserRessource extends Ressource
{
  use AddressesHelper,
    PermissionsHelper,
    RolesHelper,
    OAuthHelper;

  protected function transform(mixed $item, array|null $fields): Collection
  {
    try {
      $user = QueryBuilder::for(User::class)->withTrashed()->find($item->id);

      $transformed = collect([
        "id" => $item->id,
        "lastname" => $item->lastname,
        "firstname" => $item->firstname,
        "username" => $item->username,
        "email" => $item->email,
        "roles" => $this->isRelationRequested($fields, "roles")
          ? $this->getRolesNamesFromUser($user)
          : [],
        "permissions" => $this->isRelationRequested($fields, "permissions")
          ? $this->getPermissionsNamesFromUser($user)
          : [],
        "enabled" => !!$item->enabled,
        "password_requested_at" => $item->password_requested_at,
        "phone_number" => $item->phone_number,
        "has_newsletter" => $item->has_newsletter,
        "address" => $this->isRelationRequested($fields, "addresses")
          ? $this->getAddressesFromModel($item)
          : [],
        "oAuth" => $this->getOAuth($item),
        "created_at" => $item->created_at,
        "updated_at" => $item->updated_at,
        "deleted_at" => $item->deleted_at,
      ]);

      if (!empty($fields)) {
        $transformed = $transformed->only($fields);
      }

      return $transformed;
    } catch (UserRessourceException $e) {
      throw new UserRessourceException(__("users.transform_failed"));
    }
  }
}
