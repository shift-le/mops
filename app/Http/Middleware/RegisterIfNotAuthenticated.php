<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotManage
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('manage')->check()) {
            return redirect('/manage/login');  // 管理ログイン画面へ
        }

        return $next($request);
    }
}
