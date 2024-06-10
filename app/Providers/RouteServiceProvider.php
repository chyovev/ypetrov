<?php

namespace App\Providers;

use App\Admin\Http\Controllers\ReorderController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('web', 'api', 'anti.spam', 'banned')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        });

        $this->addReorderMacro();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Add a route macro allowing for a list of tables to have
     * their records reordered (using their 'order' columns).
     * The tables list must be passed as an array. Only tables
     * passed to the macro will be processed (where condition).
     */
    private function addReorderMacro(): void {
        Route::macro('reorder', function ($tables) {
            $regex = implode('|', $tables);

            Route::get('{table}/reorder',  [ReorderController::class, 'get_all_records'])->where('table', $regex)->name('admin.reorder');
            Route::post('{table}/reorder', [ReorderController::class, 'save_reordered_records'])->where('table', $regex);
        });
    }
}
