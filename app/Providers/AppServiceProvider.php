<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Components\Sanctum\PersonalAccessToken;
use App\Facades\Pagination;
use App\Services\PaginationService;
use App\Utils\StringUtils;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
  use StringUtils;

  /**
   * Register any application services.
   */
  public function register(): void
  {
    // Register the pagination service
    $this->app->singleton("pagination", function ($app) {
      return new PaginationService();
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Use the custom personal access token model for Sanctum
    Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

    // Load the pagination configuration
    $this->loadPaginationConfig();
  }

  /**
   * Load the pagination configuration.
   */
  private function loadPaginationConfig(): void
  {
    $modules = config("app.modules");
    foreach ($modules as $module) {
      if ($module === "Search") {
        continue;
      }

      $ressource = "App\\Http\\Modules\\Admin\\{$this->pluralize($module)}\\{$module}Ressource";

      $snake_case_module = $this->convertToSnakeCase($module);

      Pagination::define($snake_case_module, new $ressource());
    }
  }
}
