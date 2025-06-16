<?php
// app/Http/Middleware/AdminCheck.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminCheck
{
    public function handle($request, Closure $next)
    {
        Log::debug('AdminCheck 開始');

        if (!Auth::check()) {
            Log::debug('未ログイン：' . $request->path());
            return redirect('/manage/login');
        }

        Log::debug('ログイン済みユーザー：' . Auth::user()->id);

        if (!in_array(Auth::user()->ROLE_ID, ['MU01', 'NU01'])) {
            return redirect('/');
        }

        return $next($request);
    }


}
