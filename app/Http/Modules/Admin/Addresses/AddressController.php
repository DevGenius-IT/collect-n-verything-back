<?php

namespace App\Http\Modules\Admin\Addresses;

use App\Components\CRUDController;
use App\Components\ExceptionHandler;
use App\Http\Modules\Admin\Addresses\Exceptions\Rules\AddressDestroyOrRestoreValidateRulesException;
use App\Http\Modules\Admin\Addresses\Exceptions\Rules\AddressDuplicateValidateRulesException;
use App\Http\Modules\Admin\Addresses\Exceptions\Rules\AddressStoreValidateRulesException;
use App\Http\Modules\Admin\Addresses\Exceptions\Rules\AddressIndexValidateRulesException;
use App\Http\Modules\Admin\Addresses\Exceptions\Rules\AddressUpdateValidateRulesException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The address controller class.
 *
 * @package App\Http\Modules\Admin\Addresses
 * @extends CRUDController
 *
 * *****Methods*******
 * @method void __construct(AddressRepository $repository, AddressRessource $ressource)
 * @method JsonResponse index(Request $request, ExceptionHandler $_)
 * @method JsonResponse store(Request $request, ExceptionHandler $_)
 * @method JsonResponse update(int $id, Request $request, ExceptionHandler $_)
 * @method JsonResponse destroy(Request $request, ExceptionHandler $_)
 * @method JsonResponse restore(Request $request, ExceptionHandler $_)
 * @method JsonResponse duplicate(Request $request, ExceptionHandler $_)
 */
class AddressController extends CRUDController
{
  public function __construct(AddressRepository $repository, AddressRessource $ressource)
  {
    parent::__construct("address", $repository, $ressource);
  }

  public function index(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressIndexValidateRulesException();
    return parent::index($request, $exception);
  }

  public function store(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressStoreValidateRulesException();
    return parent::store($request, $exception);
  }

  public function update(int $id, Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressUpdateValidateRulesException();
    return parent::update($id, $request, $exception);
  }

  public function destroy(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressDestroyOrRestoreValidateRulesException();
    return parent::destroy($request, $exception);
  }

  public function restore(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressDestroyOrRestoreValidateRulesException();
    return parent::restore($request, $exception);
  }

  public function duplicate(Request $request, ExceptionHandler $_): JsonResponse
  {
    $exception = new AddressDuplicateValidateRulesException();
    return parent::duplicate($request, $exception);
  }
}
