<?php

namespace App\Http\Modules\Authentication;

use App\Components\Controller;
use App\Components\CRUDRules;
use App\Components\ExceptionHandler;
use App\Components\Ressource;
use App\Http\Modules\Admin\Users\UserRepository;
use App\Http\Modules\Authentication\Exceptions\AuthenticationException;
use App\Http\Modules\Authentication\Exceptions\RecoveryException;
use App\Http\Modules\Authentication\Exceptions\Rules\AuthResetPasswordValidateRulesException;
use App\Http\Modules\Authentication\Exceptions\Rules\AuthSignUpRulesValidateException;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\OAuthService;
use App\Services\RecoveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

/**
 * The Auth controller class.
 *
 * @package App\Http\Modules\Authentication
 * @extends Controller
 *
 * *****Traits*****
 * @use AuthRules
 *
 * *****Properties*****
 * @property User $model
 * @property CRUDRules $rules
 * @property OAuthService $oAuthService
 * @property RecoveryService $recoveryService
 *
 * *****Methods*******
 * @method void __construct(UserRepository $repository, User $model, OAuthService $oAuthService)
 * @method JsonResponse signUp(Request $request, AuthSignUpRulesValidateException $exception)
 * @method array initializeNewUser(array $data)
 * @method string generateToken(User $user, string $device = null)
 * @method JsonResponse signIn(Request $request)
 * @method JsonResponse forgotPassword(Request $request, string $method)
 * @method JsonResponse resetPassword(Request $request, string $token)
 * @method RedirectResponse redirectToProvider(Request $request, string $provider)
 * @method RedirectResponse handleProviderCallback(Request $request, string $provider)
 * @method string callbackFrontendUrl(string $provider, string $token)
 * @method JsonResponse signOut(Request $request)
 */
class AuthController extends Controller
{
  use AuthRules;

  /**
   * The user instance.
   *
   * @var User
   */
  protected User $model;

  /**
   * The OAuth service instance.
   *
   * @var OAuthService
   */
  protected OAuthService $oAuthService;

  /**
   * The Recovery service instance.
   *
   * @var RecoveryService
   */
  protected RecoveryService $recoveryService;

  protected UserRepository $userRepository;

  /**
   * The ressource instance.
   *
   * @var Ressource
   */
  protected Ressource $ressource;
  /**
   * Create a new controller instance.
   *
   * @param UserRepository $repository
   * @param User $model
   * @param OAuthService $oAuthService
   * @return void
   */
  public function __construct(
    UserRepository $repository,
    Ressource $ressource,
    User $model,
    OAuthService $oAuthService,
    RecoveryService $recoveryService
  ) {
    parent::__construct($repository, $ressource);
    $this->model = $model;
    $this->oAuthService = $oAuthService;
    $this->recoveryService = $recoveryService;
    $this->userRepository = $repository;
  }

  /**
   * Register a new user.
   */
  public function signUp(
    SignupRequest $request
  ): JsonResponse {
    // Valide les données avant de les envoyer au repository (validations faites au préalable)
    $data = $request->only(['username', 'lastname', 'firstname', 'email', 'password', 'password_confirmation', 'phone_number', 'type', 'stripe_id']);

    // Appel à la méthode create() du repository
    $user = $this->userRepository->create($data);

    // Générer un token d'accès personnel
    $token = $user->createToken('auth_token')->plainTextToken;

    // Retourner l'utilisateur avec le token
    return response()->json([
      'message' => 'Utilisateur créé avec succès',
      'user' => $user,
      'token' => $token,
    ], 201);
  }

  /**
   * Login a user.
   */
  public function signIn(SigninRequest $request): JsonResponse
  {
    $user = $this->userRepository->findByEmailOrUsername(($request->email == null) ? $request->username : $request->email);

    if ($user && Hash::check($request->password, $user->password)) {
      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Connexion réussie.',
        'token' => $token,
        'user' => $user,
      ], 200);
    }

    return response()->json([
      'message' => 'Identifiants incorrects.',
    ], 401);
  }

  /**
   * Reset password.
   */
  public function resetPassword(ResetPasswordRequest $request): JsonResponse
  {
    $user = User::where("email", $request->email)
      ->orWhere("username", $request->username)
      ->first();

    if (!$user) {
      return response()->json(["error" => "Utilisateur non trouvé."], 404);
    }

    // Vérifier l'ancien mot de passe
    if (!Hash::check($request->old_password, $user->password)) {
      return response()->json(["error" => "L'ancien mot de passe est incorrect."], 401);
    }

    // Mettre à jour le mot de passe
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json(["message" => "Mot de passe mis à jour avec succès."], 200);
  }

  /**
   * Logout a User.
   */
  public function signOut(Request $request): JsonResponse
  {
    $request->user()->tokens()->delete();

    return response()->json(["message" => "Déconnexion réussie"], 200);
  }
}
