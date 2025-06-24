<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ToolType1;
use App\Models\ToolType2;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function show()
    {
        $type1s = ToolType1::orderBy('DISP_ORDER')->get();
        $type2s = ToolType2::orderBy('DISP_ORDER')->get();

        $toolTypeOptions = $type2s
            ->groupBy('TOOL_TYPE2_NAME')
            ->map(function ($items, $name) {
                return [
                    'label' => $name,
                    'tool_type2_values' => $items->pluck('TOOL_TYPE2')->unique()->values(),
                ];
            })
            ->values();

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

    if ($request->filled('tool_type2_name')) {
        $label = $request->input('tool_type2_name');

        // 「共通区分（名前）」→ 共通コード（例：C01）取得
        $common = DB::table('M_COM_TOOL_TYPE')
            ->where('COM_TOOL_TYPE_NAME', $label)
            ->value('COM_TOOL_TYPE');

        if ($common) {
            // 共通コード（例：C01）→ TOOL_TYPE2群を取得
            $toolType2List = DB::table('M_TOOL_TYPE_JOIN')
                ->where('COMMON_TYPE', $common)
                ->pluck('TOOL_TYPE2');

            $query->whereIn('TOOL_TYPE2', $toolType2List);
        }
    }

    $tools = $query->paginate($request->input('per_page', 20));
    $toolTypeOptions = self::getToolTypeOptions();

    return view('tools.search', compact('tools', 'toolTypeOptions'));
}

public static function getToolTypeOptions()
{
    return DB::table('M_TOOL_TYPE_JOIN')
        ->join('M_COM_TOOL_TYPE', 'M_TOOL_TYPE_JOIN.COMMON_TYPE', '=', 'M_COM_TOOL_TYPE.COM_TOOL_TYPE')
        ->join('M_TOOL_TYPE2', function($join) {
            $join->on('M_TOOL_TYPE_JOIN.TOOL_TYPE1', '=', 'M_TOOL_TYPE2.TOOL_TYPE1')
                ->on('M_TOOL_TYPE_JOIN.TOOL_TYPE2', '=', 'M_TOOL_TYPE2.TOOL_TYPE2');
        })
        ->select(
            'M_COM_TOOL_TYPE.COM_TOOL_TYPE_NAME as label',
            'M_COM_TOOL_TYPE.COM_TOOL_TYPE',
            'M_COM_TOOL_TYPE.DISP_ORDER',
            'M_TOOL_TYPE2.TOOL_TYPE2'
        )
        ->orderBy('M_COM_TOOL_TYPE.DISP_ORDER')
        ->get()
        ->groupBy('label')
        ->map(function ($items, $label) {
            return [
                'label' => $label,
                'tool_type2_values' => $items->pluck('TOOL_TYPE2')->unique()->values(),
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
