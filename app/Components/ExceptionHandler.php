<?php

namespace App\Components;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * The exception handler class.
 *
 * @package App\Components
 * @extends Exception
 *
 * *****Properties*****
 * @property string $name
 * @property array|string $errors
 *
 * *****Methods*******
 * @method void __construct(array|string $errors, string|null $message, int $code, \Exception|null $previous)
 * @method void setErrors(array|string $errors)
 * @method array|string getErrors()
 * @method void report()
 * @method JsonResponse render(Request $request)
 */
class ExceptionHandler extends Exception
{
  /**
   * The name of the exception.
   *
   * @var string
   */
  protected string $name = "ExceptionHandler";

  /**
   * The errors that occurred during the exception.
   *
   * @var array|string
   */
  protected string|array $errors;

  /**
   * Create a new exception instance.
   *
   * @param array|string $errors
   * @param string|null $message
   * @param int $code
   * @param \Exception|null $previous
   */
  public function __construct(
    array|string $errors = [],
                 $message = null,
                 $code = 0,
    ?Exception   $previous = null
  )
  {
    $message = $message ?? __("components.exception_handler.message", ["name" => $this->name]);
    parent::__construct($message, $code, $previous);
    $this->setErrors($errors);
  }

  /**
   * Set the errors for the exception.
   *
   * @param array|string $errors
   * @return void
   */
  protected function setErrors(array|string $errors): void
  {
    $this->errors = $errors;
  }

  /**
   * Get the errors for the exception.
   *
   * @return array|string
   */
  public function getErrors(): array|string
  {
    return $this->errors;
  }

  /**
   * Report the exception.
   *
   * @return void
   */
  public function report(): void
  {
    Log::error($this->getMessage(), ["errors" => $this->errors]);
  }

  /**
   * Render the exception into an HTTP response.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function render(Request $request): JsonResponse
  {
    Log::error($this->name . "Exception", [
      "ip" => $request->ip(),
      "request_method" => $request->method(),
      "request_url" => $request->fullUrl(), // need to protect this
      "request_data" => $request->all(),  // need to protect this
      "errors" => $this->errors,
    ]);

    return response()->json(
      [
        "error" => $this->name . "Exception",
        "message" => $this->getMessage(),
        "errors" => $this->errors,
      ],
      $this->getCode() !== 0 ? $this->getCode() : 400
    );
  }
}
