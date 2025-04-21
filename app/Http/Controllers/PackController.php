<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Services\PackService;
use App\Validators\PackValidator;

class PackController extends CrudController
{
    protected string $modelClass = Pack::class;
    protected string $modelName = 'Pack';

    protected $service;

    public function __construct(PackService $service, PackValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
