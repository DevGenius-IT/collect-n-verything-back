<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Http\Request;

class UserController extends CrudController
{
    protected string $modelClass = User::class;
    protected string $modelName = 'Utilisateur';

    protected $service;

    public function __construct(UserService $service, UserValidator $validator)
    {
        parent::__construct($service, $validator);
    }

    public function store(Request $request)
    {
        $rules = call_user_func([$this->validator, 'rules']);
        $messages = call_user_func([$this->validator, 'messages']);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'La validation a échoué.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $model = $this->modelClass;
        $record = $model::create($data);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            'email' => $record->email,
            'name'  => $record->firstname . ' ' . $record->lastname,
            'phone' => $record->phone_number ?? null,
        ]);

        $record->stripe_id = $customer->id;
        $record->save();

        return response()->json([
            'status'  => 'success',
            'message' => class_basename($model) . ' créé avec succès et lié à Stripe.',
            'data'    => $record
        ], 201);
    }
}
