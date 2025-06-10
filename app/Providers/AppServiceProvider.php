<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MToolType1;
use App\Models\MToolType2;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    View::composer('*', function ($view) {
        $type1s = MToolType1::orderBy('DISPLAY_TURN')->get();
        $type2s = MToolType2::orderBy('DISPLAY_TURN')->get();

        $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
            $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
            return [
                'label' => $label,
                'children' => $items,
            ];
        });

        $view->with('toolTypeOptions', $toolTypeOptions);
    });
    }
}
