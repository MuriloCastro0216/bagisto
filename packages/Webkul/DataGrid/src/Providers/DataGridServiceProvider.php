<?php

namespace Webkul\DataGrid\Providers;

use Illuminate\Support\ServiceProvider;

class DataGridServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
