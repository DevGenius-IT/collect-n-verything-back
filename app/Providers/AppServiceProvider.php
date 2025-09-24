<?php

namespace App\Providers;

use App\Services\CrudService;
use App\Services\PaginationService;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Singleton global de la pagination
        $this->app->singleton(PaginationService::class, function () {
            return new PaginationService();
        });
    }

    public function boot()
    {
        //
    }
}
