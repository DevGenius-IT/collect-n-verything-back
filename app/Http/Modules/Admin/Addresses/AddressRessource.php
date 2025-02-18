<?php

namespace App\Http\Modules\Admin\Addresses;

use App\Components\Ressource;
use App\Helpers\SchoolsHelper;
use App\Helpers\UsersHelper;
use App\Helpers\WorkplacesHelper;
use App\Http\Modules\Admin\Addresses\Exceptions\AddressRessourceException;
use App\Models\Address;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * The address ressource class.
 *
 * @package App\Http\Modules\Admin\Addresses
 * @extends Ressource
 *
 * *****Traits*****
 * @use UsersHelper
 *
 * *****Methods*****
 * @method Collection transform(mixed $item, array|null $fields)
 */
class AddressRessource extends Ressource
{
  use UsersHelper;

  protected function transform(mixed $item, array|null $fields): Collection
  {
    try {
      $item = QueryBuilder::for(Address::class)->withTrashed()->find($item->id);

      $transformed = collect([
        "id" => $item->id,
        "street" => $item->street,
        "additional" => $item->additional,
        "locality" => $item->locality,
        "zip_code" => $item->zip_code,
        "city" => $item->city,
        "department" => $item->department,
        "country" => $item->country,
        "users" => $this->isRelationRequested($fields, "users")
          ? $this->getUsersFromModel($item)
          : [],
        "created_at" => $item->created_at,
        "updated_at" => $item->updated_at,
        "deleted_at" => $item->deleted_at,
      ]);

      if (!empty($fields)) {
        $transformed = $transformed->only($fields);
      }

      return $transformed;
    } catch (AddressRessourceException) {
      throw new AddressRessourceException(__("addresses.transform_failed"));
    }
  }
}
