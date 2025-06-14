<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManageAuth
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !in_array(Auth::user()->ROLE_ID, ['MA01', 'NA01'])) {
            return redirect()->route('manage.login')->with('error', '管理画面の権限がありません');
        }

        return $next($request);
    }
}
