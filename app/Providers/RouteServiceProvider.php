<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'inertiaAdmin'])
                ->name('admin.')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'inertiaDoctor' ])
                ->name('doctor.')
                ->prefix('doctor')
                ->group(base_path('routes/doctor.php'));

            Route::middleware(['web', 'inertiaRadiologist' ])
                ->name('radiologist.')
                ->prefix('radiologist')
                ->group(base_path('routes/radiologist.php'));

            Route::middleware(['web', 'inertiaLaboratorist' ])
                ->name('laboratorist.')
                ->prefix('laboratorist')
                ->group(base_path('routes/laboratorist.php'));

            Route::middleware(['web', 'inertiaPatient' ])
                ->name('patient.')
                ->prefix('patient')
                ->group(base_path('routes/patient.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
