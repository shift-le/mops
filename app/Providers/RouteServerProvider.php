<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServerProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

// RouteServiceProvider.php の routes 関数

        $this->routes(function () {
            // 通常のルート
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // 管理用ルート
            Route::prefix('manage')
                ->middleware(['web', \App\Http\Middleware\AdminCheck::class])  // AdminCheck を追加
                ->group(base_path('routes/manage.php'));
        });

    }
}
