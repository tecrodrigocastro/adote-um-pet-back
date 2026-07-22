<?php

declare(strict_types=1);

namespace App\Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(
            app_path('Modules/Auth/Infrastructure/Routes/auth.php')
        );
    }
}
