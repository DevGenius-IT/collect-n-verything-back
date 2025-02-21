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
   * Register a new User.
   *
   * @param Request $request
   * @return JsonResponse
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
   * Login a User.
   *
   * @param Request $request
   * @return JsonResponse
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

    // Retourner une erreur si la connexion échoue
    return response()->json([
      'message' => 'Identifiants incorrects.',
    ], 401);
  }

  /**
   * Verify the user token.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function verify(Request $request): JsonResponse
  {
    try {
      $device = $request->input("device");
      $request->user()->tokens()->delete();
      $token = $this->generateToken($request->user(), $device);
      $user = $this->repository->show($request->user()["id"]);

      return response()->json([
        "token" => $token,
        "user" => $this->ressource->toArray($user, null),
      ]);
    } catch (AuthenticationException $e) {
      throw new AuthenticationException(__("authentication.invalid_credentials"), null, 401);
    }
  }

  /**
   * Forgot password.
   *
   * @param Request $request
   * @param string $method
   * @return JsonResponse
   */
  public function forgotPassword(Request $request, string $method): JsonResponse
  {
    try {
      return $this->recoveryService->sendToken($method, $request->input("identifier"));
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Reset password.
   *
   * @param Request $request
   * @param string $token
   * @return JsonResponse
   */
  public function resetPassword(Request $request, string $token): JsonResponse
  {
    try {
      $user = User::where("reset_password_token", $token)->first();

      if (!$user) {
        throw new RecoveryException(__("authentication.invalid_token"), null, 400);
      }

      $validatedParams = Validator::make($request->all(), $this->resetPasswordRules);

      if ($validatedParams->fails()) {
        throw new AuthResetPasswordValidateRulesException(
          $validatedParams->errors()->toArray(),
          null,
          400
        );
      }

      $user
        ->forceFill([
          "password" => Hash::make($request->input("password")),
          "reset_password_token" => null,
          "password_requested_at" => null,
        ])
        ->save();

      return response()->json(["message" => __("authentication.password_reset")]);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Redirect the user to the OAuth Provider authentication page.
   *
   * @param Request $request
   * @param string $provider
   * @return RedirectResponse
   */
  public function redirectToProvider(Request $request, string $provider): RedirectResponse
  {
    try {
      return $this->oAuthService->redirectToProvider($request, $provider);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Obtain the user information from the OAuth Provider.
   *
   * @param Request $request
   * @param string $provider
   * @return RedirectResponse
   */
  public function handleProviderCallback(Request $request, string $provider): RedirectResponse
  {
    try {
      $token = $this->oAuthService->handleProviderCallback($request, $provider);
      return Redirect::away($this->callbackFrontendUrl($provider, $token));
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Generate the callback URL for the OAuth Provider.
   *
   * @param string $provider
   * @param string $token
   * @return string
   */
  private function callbackFrontendUrl(string $provider, string $token): string
  {
    return Env("APP_FRONTEND_URL") . "/" . $provider . "/callback?token=" . $token;
  }

  /**
   * Logout a User.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function signOut(Request $request): JsonResponse
  {
    $request->user()->tokens()->delete();

    return response()->json(["message" => __("authentication.sign_out")]);
  }
}
