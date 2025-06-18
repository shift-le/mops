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
        $request->validate([
            'PASSWORD' => ['required', 'string', 'min:6'],
        ]);

        $user = Auth::user();
        $user->PASSWORD = Hash::make($request->input('PASSWORD'));
        $user->save();

        return view('users.complete');
    }
}
