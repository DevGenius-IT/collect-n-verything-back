<?php

namespace App\Services;

use App\Components\ExceptionHandler;
use App\Http\Modules\Authentication\Exceptions\OAuthException;
use App\Providers\OAuth\Google;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OAuthService
{
  use Google;

  /**
   * List of OAuth Providers.
   *
   * @var array
   */
  protected array $providers;

  /**
   * OAuthService constructor.
   */
  public function __construct()
  {
    $this->providers = config("services.oauth");
  }

  /**
   * Redirect the user to the OAuth Provider authentication page.
   *
   * @param  Request $request
   * @param  string  $provider
   * @return RedirectResponse
   */
  public function redirectToProvider(Request $request, string $provider): RedirectResponse
  {
    try {
      $this->checkProvider($provider);

      return Socialite::driver($provider)->redirect();
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }

  /**
   * Check if the OAuth Provider is valid.
   *
   * @param  string  $provider
   * @return void
   */
  public function checkProvider(string $provider): void
  {
    if (!in_array($provider, $this->providers)) {
      throw new OAuthException(__("authentication.invalid_oauth"), null, 400);
    }
  }

  /**
   * Obtain the user information from the OAuth Provider.
   *
   * @param  Request $request
   * @param  string  $provider
   * @return string
   */
  public function handleProviderCallback(Request $request, string $provider): RedirectResponse
  {
    try {
      $this->checkProvider($provider);

      $userProvider = Socialite::driver($provider)->stateless()->user();

      $user = $this->{"handle" . ucfirst($provider) . "User"}($userProvider, ["user"]);

      return $user->createToken($provider . "_api_token")->plainTextToken;
    } catch (ExceptionHandler $e) {
      return $e->render($request);
    }
  }
}
