<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // MU01かNU01だけ通す
        if (!$user || !in_array($user->ROLE_ID, ['MA01', 'NA01'])) {
            abort(403, '管理者専用ページです');
        }

        return $next($request);
    }
}
