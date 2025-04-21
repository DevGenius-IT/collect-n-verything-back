<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Validators\UserValidator;

class UserController extends CrudController
{
    protected string $modelClass = User::class;
    protected string $modelName = 'Utilisateur';

    protected $service;

    public function __construct(UserService $service, UserValidator $validator)
    {
        parent::__construct($service, $validator);
    }
}
