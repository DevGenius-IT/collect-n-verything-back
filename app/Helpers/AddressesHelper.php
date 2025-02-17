<?php

namespace App\Helpers;

use App\Http\Modules\Admin\Addresses\AddressRessource;
use App\Models\Address;
use App\Models\School;
use App\Models\User;
use App\Models\Workplace;
use App\Utils\HelperUtils;
use Illuminate\Support\Collection;

/**
 * The addresss helper trait.
 *
 * @package App\Helpers
 *
 * ******Traits******
 * @use ExceptionsHelper
 * @use HelperUtils
 *
 * *****Properties*****
 * @property array $addressSingleRelatedModels
 *
 * *****Methods*****
 * @method Collection|null getAddressesFromModel(mixed $model)
 */
trait AddressesHelper
{
  use ExceptionsHelper, HelperUtils;

  /**
   * The models that have a single address.
   *
   * @var array
   */
  private array $addressSingleRelatedModels = [School::class, User::class, Workplace::class];

  /**
   * Get the addresss of the given model.
   *
   * @param mixed $model
   * @return Collection|null
   * @throws \Exception
   */
  private function getAddressesFromModel($model): Collection|null
  {
    try {
      $fields = [
        "id",
        "street",
        "additional",
        "locality",
        "zip_code",
        "city",
        "department",
        "country",
        "addresss",
        "users",
        "workplaces",
        "created_at",
        "updated_at",
        "deleted_at",
      ];

      $this->removeFieldFromModel($model, $fields);
      
      if (in_array(get_class($model), $this->addressSingleRelatedModels)) {
        if ($model->address === null) {
          return null;
        }
        return (new AddressRessource())->transform($model->address, $fields);
      }
      
      return $model->addresses->map(function ($address) use ($fields) {
        return (new AddressRessource())->transform($address, $fields);
      });
    } catch (\Exception) {
      $exceptionClass = $this->getExceptionClassFromModel($model);
      throw new $exceptionClass(
        __($this->getExceptionTranslationKeyFromModel($model) . ".transform_failed")
      );
    }
  }
}
