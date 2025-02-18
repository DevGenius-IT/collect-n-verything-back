<?php

namespace App\Helpers;

use App\Http\Modules\Admin\Addresses\AddressRules;
use App\Http\Modules\Admin\Users\UserRules;

/**
 * The rules helper trait.
 *
 * @package App\Helpers
 *
 * ******Traits******
 * @use AddressRules
 * @use UserRules
 */
trait RulesHelper
{
  use AddressRules, UserRules;
}
