<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServerProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            // 通常のルート
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // 管理用ルート
            Route::prefix('manage')       // /manage にプレフィックス
                ->middleware('web')       // 必要なら 'auth' も追加できる
                ->group(base_path('routes/manage.php'));
        });
    }
}
