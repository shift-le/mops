<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class MockLoginController extends Controller
{
    public function loginAs($userId)
    {
        $user = User::where('USER_ID', $userId)->first();

        if ($user) {
            Auth::login($user);
            logger('ログイン成功: ' . $user->USER_ID);
            return redirect('/category');
        }

        return response("ユーザー {$userId} が見つかりません", 404);
    }
}
