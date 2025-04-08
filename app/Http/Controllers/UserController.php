<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Validators\UserValidator;

class UserController extends CrudController
{
    public function __construct(UserService $service, UserValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
