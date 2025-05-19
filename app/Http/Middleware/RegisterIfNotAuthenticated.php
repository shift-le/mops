<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!$request->user()) {
    //         $user = new User;
    //         $user->user_id = (string)Str::uuid();
    //         $user->name = '';
    //         $user->email = null;
    //         $user->email_verified_at = null;
    //         $user->password = null;
    //         $user->save();

    //         Auth::Login($user);
    //     }

    //     return $next($request);
    // }
}
