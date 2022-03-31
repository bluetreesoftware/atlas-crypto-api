<?php

namespace App\Providers;

use App\Http\Controllers\Consumer\Wallet\Transaction\TransactionController;
use App\Http\Controllers\Consumer\Wallet\WalletController;
use App\Models\Wallet;
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
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->setupModelRouteKeys();
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

    protected function setupModelRouteKeys()
    {
        Route::bind('wallet', function (string | int $id, \Illuminate\Routing\Route $route) {

            if (in_array($route->controller::class, [WalletController::class, TransactionController::class])) {
                return  Wallet::where('external_id', $id)->firstOrFail();
            }

            return Wallet::findOrFail($id);
        });
    }
}
