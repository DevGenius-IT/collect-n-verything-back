<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\AddressService;
use App\Validators\AddressValidator;

class AddressController extends CrudController
{
    protected string $modelClass = Address::class;
    protected string $modelName = 'Adresse';

    protected $service;

    public function __construct(AddressService $service, AddressValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
