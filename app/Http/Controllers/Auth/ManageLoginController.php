<?php

// app/Http/Controllers/Auth/ManageLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManageLoginController extends Controller
{
    protected $redirectTo = '/manage/top'; // ← これで固定できる

    public function show()
    {
        return view('manage.login'); // ログイン画面
    }

    // public function login(Request $request)
    // {
    //     $credentials = [
    //         'USER_ID' => $request->input('USER_ID'),
    //         'password' => $request->input('password') // ← これは OK
    //     ];

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         if (!in_array($user->ROLE_ID, ['MA01', 'NA01'])) {
    //             Auth::logout();
    //             return redirect()->route('managelogin.show')->with('error', '管理権限がありません');
    //         }

    //         return redirect('/manage/top');
    //     }

    //     return redirect()->route('managelogin.show')->with('error', 'ログイン情報が正しくありません');
    // }
    public function login(Request $request)
    {
        $credentials = [
            'USER_ID'  => $request->input('USER_ID'),
            'password' => $request->input('password'),
        ];

        \Log::debug('管理ログイン try', $credentials);

        if (Auth::guard('manage')->attempt($credentials)) {
            $user = Auth::guard('manage')->user();
            if (in_array($user->ROLE_ID, ['MA01','NA01'])) {
                \Log::debug('管理ログイン OK', ['user'=>$user]);
                return redirect()->intended('/manage/top');
            }
            Auth::guard('manage')->logout();
            \Log::debug('ROLE拒否', ['ROLE'=>$user->ROLE_ID]);
        } else {
            \Log::debug('認証失敗');
        }

        return redirect()->route('managelogin.show')
            ->with('error','ログイン情報が正しくありません');
    }


    public function logout(Request $request)
    {
        Auth::guard('manage')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('managelogin.login');
    }
}

