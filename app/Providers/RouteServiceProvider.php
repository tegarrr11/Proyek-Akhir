<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->routes(function () {
            // ✅ Aktifkan routes API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // ✅ Aktifkan routes WEB
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
