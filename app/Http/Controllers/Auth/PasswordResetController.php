<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('passwordreset.request');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:USERS,email',
            ],
        ], [
            'email.exists' => 'メールアドレスが見つかりません。',
        ]);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('password.sendcomplete')
            : back()->withErrors(['email' => __($status)]);
    }

    public function sendComplete()
    {
        return view('passwordreset.sendcomplete');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('passwordreset.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate(
            [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|string|min:8',
            ],
            [
                'token.required' => 'トークンが無効です。',
                'email.required' => 'メールアドレスは必須です。',
                'email.email'    => 'メールアドレスの形式が正しくありません。',
                'password.required' => 'パスワードを入力してください。',
                'password.min'      => 'パスワードは8文字以上で入力してください。',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $hashed = \Illuminate\Support\Facades\Hash::make($password);

                Log::info('保存対象パスワード', ['plain' => $password, 'hashed' => $hashed]);

                $user->PASSWORD = $hashed;
                $user->save();

                Log::info('ユーザー保存結果: 成功');

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('password.complete')
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function complete()
    {
        return view('passwordreset.complete');
    }
}
