<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignoutRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api authentification",
 *      description="Implementation authentification api"
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/auth/signin",
     *     summary="Connexion",
     *     tags={"Authentification"},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "username", "password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Succès"
     *     )
     * )
     */
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
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/auth/signup",
     *     summary="Création de compte utilisateur",
     *     tags={"Authentification"},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lastname", "firstname", "username", "email", "password", "password_confirmation", "phone_number", "type"},
     *             @OA\Property(property="lastname", type="string"),
     *             @OA\Property(property="firstname", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password"),
     *             @OA\Property(property="phone_number", type="string"),
     *             @OA\Property(property="type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Succès"
     *     )
     * )
     */
    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'lastname'      => $request->lastname,
            'firstname'     => $request->firstname,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone_number'  => $request->phone_number,
            'type'          => $request->type,
            'address_id'    => $request->address_id,
            'stripe_id'     => $request->stripe_id,
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/auth/signout",
     *     summary="Déconnexion sécurisée de l'utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Déconnexion réussie"),
     *     @OA\Response(response=401, description="Identifiants invalides")
     * )
     */

    public function signout(SignoutRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants incorrects.',
                'errors' => [
                    'email' => ['L’adresse email ou le mot de passe est incorrect.']
                ]
            ], 401);
        }

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/auth/reset-password",
     *     summary="Changement de mot de passe",
     *     tags={"Authentification"},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "old_password", "password", "password_confirmation"},
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="old_password", type="string", format="password"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Succès"
     *     )
     * )
     */
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
