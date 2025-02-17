<?php

namespace App\Http\Modules\Admin\Addresses;

use App\Components\Repository;
use App\Helpers\UsersHelper;
use App\Models\Address;

/**
 * The address repository class.
 *
 * @package App\Http\Modules\Admin\Addresses
 * @extends Repository
 *
 * *****Traits*****
 * @use UsersHelper
 *
 * ******Methods*******
 * @method public __construct(Address $model, AddressRessource $ressource)
 */
class AddressRepository extends Repository
{
  use UsersHelper;

  public function __construct(Address $model, AddressRessource $ressource)
  {
    parent::__construct($model, $ressource);
  }
}
