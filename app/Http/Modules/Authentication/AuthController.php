<?php

namespace App\Http\Modules\Authentication;

use App\Components\Controller;
use App\Components\CRUDRules;
use App\Components\ExceptionHandler;
use App\Enums\RolesEnum;
use App\Http\Modules\Admin\Users\UserRepository;
use App\Http\Modules\Admin\Users\UserRessource;
use App\Http\Modules\Authentication\Exceptions\AuthenticationException;
use App\Http\Modules\Authentication\Exceptions\RecoveryException;
use App\Http\Modules\Authentication\Exceptions\Rules\AuthResetPasswordValidateRulesException;
use App\Http\Modules\Authentication\Exceptions\Rules\AuthSignUpRulesValidateException;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Services\OAuthService;
use App\Services\RecoveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
 * @method void __construct(UserRepository $repository, UserRessource $ressource, User $model, OAuthService $oAuthService)
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
   * The service instance.
   *
   * @var CRUDRules
   */
  protected CRUDRules $rules;

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

  /**
   * Create a new controller instance.
   *
   * @param UserRepository $repository
   * @param UserRessource $ressource
   * @param User $model
   * @param OAuthService $oAuthService
   * @return void
   */
  public function __construct(
    UserRepository $repository,
    UserRessource $ressource,
    User $model,
    OAuthService $oAuthService,
    RecoveryService $recoveryService
  ) {
    parent::__construct($repository, $ressource);
    $this->model = $model;
    $this->rules = new CRUDRules("user");
    $this->oAuthService = $oAuthService;
    $this->recoveryService = $recoveryService;
  }

  /**
   * Register a new User.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function signUp(
    Request $request,
    AuthSignUpRulesValidateException $exception
  ): JsonResponse {
    try {
      $data = $this->initializeNewUser($request->all());
      $validatedParams = $this->rules->store($data, $exception);

      $user = $this->repository->store($validatedParams);
      $user = $this->model->find($user["id"]);

      $token = $this->generateToken($user);

      $user = $this->ressource->toArray($user, null);

      return response()->json(
        [
          "token" => $token,
          "user" => $user,
        ],
        201
      );
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Validate the data for a new User.
   *
   * @param array $data
   * @return array
   * @throws AuthSignUpRulesValidateException
   */
  private function initializeNewUser(array $data): array
  {
    try {
      if (isset($data["roles"])) {
        unset ($data["roles"]);
      }

      if (!isset($data["username"]) && isset($data["email"])) {
        // keep the starting part of the email address before the @ symbol
        $data["username"] = explode("@", $data["email"])[0]; // e.g. john.doe from john.doe@exemple.com
      }

      return $data;
    } catch (AuthSignUpRulesValidateException $e) {
      throw new AuthSignUpRulesValidateException(
        __("authentication.sign_up_data_failed"),
        null,
        400
      );
    }
  }

  /**
   * Generate a token for the user.
   *
   * @param User $user
   * @param string|null $device
   * @return string
   */
  private function generateToken(User $user, string $device = null): string
  {
    return $user->createToken($device ?? "api_token")->plainTextToken;
  }

  /**
   * Login a User.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function signIn(Request $request): JsonResponse
  {
    try {
      $credentials = $request->only("email", "password");
      $device = $request->input("device");

      if (!auth()->guard("web")->attempt($credentials)) {
        throw new AuthenticationException(__("authentication.invalid_credentials"), null, 401);
      }

      $token = $this->generateToken(auth()->guard("web")->user(), $device);

      $user = $this->repository->show(auth()->guard("web")->id());

      return response()->json([
        "token" => $token,
        "user" => $this->ressource->toArray($user, null),
      ]);
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
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
