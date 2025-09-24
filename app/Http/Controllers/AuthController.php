<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Customer;

class AuthController extends Controller
{

    public function signin(SigninRequest $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Identifiants invalides.',
                'errors'  => [
                    'auth' => ['Les identifiants fournis sont incorrects.']
                ]
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'lastname'      => $request->lastname,
            'firstname'     => $request->firstname,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone_number'  => $request->phone_number,
            'address_id'    => $request->address_id,
            'stripe_id'     => $request->stripe_id,
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            'email' => $user->email,
            'name'  => $user->firstname . ' ' . $user->lastname,
            'phone' => $user->phone_number,
        ]);

        $user->stripe_id = $customer->id;
        $user->save();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'L\'ancien mot de passe est incorrect.',
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès.',
            'token'   => $token,
        ], 200);
    }
}
