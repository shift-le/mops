<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ToolType1;
use App\Models\ToolType2;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HeaderController;

class LoginController extends Controller
{
public function show()
{
    $type1s = ToolType1::orderBy('DISP_ORDER')->get();
    $type2s = ToolType2::orderBy('DISP_ORDER')->get();

    $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
        $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
        return [
            'label' => $label,
            'children' => $items,
        ];
    });

    return view('auth.login', [
        'toolTypeOptions' => $toolTypeOptions,
    ]);
}


    public function loginAs(Request $request)
    {
        $request->validate([
            'USER_ID' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'USER_ID' => $request->input('USER_ID'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('top');
        }

        return back()->with('error', 'ログインに失敗しました');
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

        $type1s = ToolType1::orderBy('DISP_ORDER')->get();
        $type2s = ToolType2::orderBy('DISP_ORDER')->get();
        $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($group, $toolType1Id) use ($type1s) {
            $type1Name = optional($type1s->firstWhere('TOOL_TYPE1', $toolType1Id))->TOOL_TYPE1_NAME ?? '未定義';
            return [
                'label' => $type1Name,
                'children' => $group,
            ];
        });

        return view('tools.search', compact('tools', 'toolTypeOptions'));
    }
        public static function getToolTypeOptions()
    {
        $type1s = ToolType1::orderBy('DISP_ORDER')->get();
        $type2s = ToolType2::orderBy('DISP_ORDER')->get();

        return $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
            $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
            return [
                'label' => $label,
                'children' => $items,
            ];
        });
    }
}
