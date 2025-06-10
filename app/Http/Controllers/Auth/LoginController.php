<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MToolType1;
use App\Models\MToolType2;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
public function show()
{
    $type1s = MToolType1::orderBy('DISPLAY_TURN')->get();
    $type2s = MToolType2::orderBy('DISPLAY_TURN')->get();

    $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
        $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
        return [
            'label' => $label,
            'children' => $items,
        ];
    });

    return view('auth.login', compact('toolTypeOptions'));
}

public function loginAs(Request $request)
{
    $request->validate([
        'USER_ID' => 'required|string',
        'password' => 'required|string',
    ]);

    $mockIds = ['user001', 'admin001', 'nakajima001'];

    if (in_array($request->input('USER_ID'), $mockIds)) {
        // モックユーザーなら、mockloginAs() 呼び出し
        return $this->mockloginAs($request);
    }

    $credentials = [
        'USER_ID' => $request->input('USER_ID'),
        'PASSWORD' => $request->input('password'),
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/category');
    }

    return back()->with('error', 'ログインに失敗しました');
}

public function mockloginAs(Request $request)
{
    $mockUsers = [
        'user001' => 'password',
        'admin001' => 'password',
        'nakajima001' => 'password',
    ];

    $inputId = $request->input('USER_ID');
    $inputPass = $request->input('password');

    if (!array_key_exists($inputId, $mockUsers)) {
        return back()->with('error', '未登録のモックユーザーIDです');
    }

    if ($mockUsers[$inputId] !== $inputPass) {
        return back()->with('error', 'パスワードが一致しません（モック）');
    }

    $user = User::where('USER_ID', $inputId)->first();
    if (!$user) {
        return back()->with('error', 'ユーザーが見つかりません（モック）');
    }

    Auth::login($user);
    return redirect('/cart');
}

    public function logout(Request $request)
    {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
    }

public function index(Request $request)
{
    $query = Tool::query();

    // ツール区分2による絞り込み
    if ($request->filled('tool_type2')) {
        $query->where('TOOL_TYPE2', $request->input('tool_type2'));
    }

    $tools = $query->paginate(20);

    $type1s = MToolType1::orderBy('DISPLAY_TURN')->get();
    $type2s = MToolType2::orderBy('DISPLAY_TURN')->get();
    $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($group, $toolType1Id) use ($type1s) {
        $type1Name = optional($type1s->firstWhere('TOOL_TYPE1', $toolType1Id))->TOOL_TYPE1_NAME ?? '未定義';
        return [
            'label' => $type1Name,
            'children' => $group,
        ];
    });

    return view('tools.search', compact('tools', 'toolTypeOptions'));
}

}
