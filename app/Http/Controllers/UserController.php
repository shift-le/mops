<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GeneralClass;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load('soshiki1', 'soshiki2', 'thuzaiin');

        $postCode = $user->thuzaiin->POST_CODE ?? '';
        $formattedPostCode = (strlen($postCode) === 7) ? substr($postCode, 0, 3) . '-' . substr($postCode, 3) : $postCode;

        $prefectureName = null;
        if ($user->thuzaiin && $user->thuzaiin->PREFECTURE) {
            $prefRecord = GeneralClass::where('TYPE_CODE', 'PREFECTURE')
                ->where('KEY', $user->thuzaiin->PREFECTURE)
                ->first();
            $prefectureName = $prefRecord ? $prefRecord->VALUE : null;
        }

        return view('users.edit', [
            'user' => $user,
            'formattedPostCode' => $formattedPostCode,
            'prefectureName' => $prefectureName,
        ]);
    }

public function complete(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'PASSWORD' => [
            'required',
            'string',
            'min:8'
        ],
    ], [
        'PASSWORD.required' => 'パスワードを入力してください。',
        'PASSWORD.min' => 'パスワードは8文字以上で入力してください。',
    ]);

    if ($validator->fails()) {
        // エラーがある場合は、再描画用に edit ビューを返す
        $user = Auth::user();
        $user->load('soshiki1', 'soshiki2', 'thuzaiin');

        $postCode = $user->thuzaiin->POST_CODE ?? '';
        $formattedPostCode = (strlen($postCode) === 7) ? substr($postCode, 0, 3) . '-' . substr($postCode, 3) : $postCode;

        $prefectureName = null;
        if ($user->thuzaiin && $user->thuzaiin->PREFECTURE) {
            $prefRecord = GeneralClass::where('TYPE_CODE', 'PREFECTURE')
                ->where('KEY', $user->thuzaiin->PREFECTURE)
                ->first();
            $prefectureName = $prefRecord ? $prefRecord->VALUE : null;
        }

        return response()->view('users.edit', [
            'user' => $user,
            'formattedPostCode' => $formattedPostCode,
            'prefectureName' => $prefectureName,
            'errors' => $validator->errors(),
        ]);
    }

    // 正常時
    $user = Auth::user();
    $user->PASSWORD = \Hash::make($request->input('PASSWORD'));
    $user->save();

    return view('users.complete');
}

}
