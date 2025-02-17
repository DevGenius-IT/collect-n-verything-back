<?php

use App\Http\Middleware\CRUDMiddleware;
use App\Http\Middleware\DestroyProtectionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    api: __DIR__ . "/../routes/api.php",
    apiPrefix: ENV("API_VERSION", "v1"),
    commands: __DIR__ . "/../routes/console.php"
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->trustHosts();
    $middleware->alias([
      "role" => RoleMiddleware::class,
      "permission" => PermissionMiddleware::class,
      "role_or_permission" => RoleOrPermissionMiddleware::class,
      "crud" => CRUDMiddleware::class,
      "destroy-protection" => DestroyProtectionMiddleware::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->dontReportDuplicates();
  })
  ->create();
