<?php

namespace App\Services;

use App\Http\Modules\Authentication\Exceptions\RecoveryException;
use App\Providers\Recovery\Email;
use Illuminate\Http\JsonResponse;

class RecoveryService
{
  use Email;

  /**
   * List of recovery methods.
   *
   * @var array
   */
  protected array $methods;

  /**
   * RecoveryService constructor.
   */
  public function __construct()
  {
    $this->methods = config("services.recovery");
  }

  /**
   * Send the recovery token to the user.

   * @param string $method
   * @param string $identifier
   * @return JsonResponse
   * @throws RecoveryException
   */
  public function sendToken(string $method, string $identifier): JsonResponse
  {
    try {
      $this->checkMethod($method);

      return $this->{"sendTokenBy" . ucfirst($method)}($identifier);
    } catch (RecoveryException $e) {
      throw new RecoveryException(
        $e->getErrors() ?? __("authentication.failed_recovery"),
        null,
        400
      );
    }
  }

  /**
   * Check if the recovery method is valid.
   *
   * @param string $method
   * @return void
   * @throws RecoveryException
   */
  public function checkMethod(string $method): void
  {
    if (!in_array($method, $this->methods)) {
      throw new RecoveryException(__("authentication.invalid_recovery"), null, 400);
    }
  }
}
