<?php


namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hinmei;
use App\Models\Tool;
use App\Models\Favorite;
use App\Models\ToolType1;
use App\Models\ToolType2;
use App\Models\GeneralClass;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
public function search(Request $request)
{
    $hinmeiCode = $request->query('hinmei');
    $ryoikiCode = $request->query('ryoiki');
    $sort = $request->query('sort');
    $order = $request->query('order', 'asc');
    $toolType2 = $request->query('tool_type2');
    $toolType2Name = $request->query('tool_type2_name');
    $perPage = $request->query('per_page', 10);
    $keyword = $request->query('keyword');
    $date = $request->query('mops_add_date');

    $query = Tool::query();

    $hinmei = null;
    $searchParts = [];

    // 表示対象条件
    $query->where('DEL_FLG', 0)
        ->whereDate('MOPS_START_DATE', '<=', now())
        ->whereDate('MOPS_END_DATE', '>=', now());

    // 品名によるフィルタ
    if ($hinmeiCode) {
        $query->where('HINMEI', $hinmeiCode);
        $hinmei = Hinmei::where('HINMEI_CODE', $hinmeiCode)->first();
        $searchParts[] = '品名「' . ($hinmei->HINMEI_NAME ?? '未定義') . '」';
    }

    // 領域によるフィルタ（Tool.HINMEI = M_RYOIKI.RYOIKI_CODE）
    if ($ryoikiCode) {
        $query->where('HINMEI', function ($sub) use ($ryoikiCode) {
            $sub->select('RYOIKI_CODE')
                ->from('M_RYOIKI')
                ->where('RYOIKI_CODE', $ryoikiCode);
        });

        $ryoikiName = \App\Models\Ryoiki::where('RYOIKI_CODE', $ryoikiCode)->value('RYOIKI_NAME');
        $searchParts[] = '領域「' . ($ryoikiName ?? '未定義') . '」';
    }

    // ツール区分（個別ID）
    if ($toolType2) {
        $query->where('TOOL_TYPE2', $toolType2);
        $toolType = ToolType2::where('TOOL_TYPE2', $toolType2)->first();
        $searchParts[] = 'ツール区分「' . ($toolType->TOOL_TYPE2_NAME ?? '未定義') . '」';
    }

    // ツール区分（共通名称）
    if ($toolType2Name) {
        $common = DB::table('M_COM_TOOL_TYPE')
            ->where('COM_TOOL_TYPE_NAME', $toolType2Name)
            ->value('COM_TOOL_TYPE');

        if ($common) {
            $toolType2List = DB::table('M_TOOL_TYPE_JOIN')
                ->where('COMMON_TYPE', $common)
                ->pluck('TOOL_TYPE2');

            $query->whereIn('TOOL_TYPE2', $toolType2List);
            $searchParts[] = 'ツール区分「' . $toolType2Name . '」';
        }
    }

    // キーワード検索
    if ($keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('TOOL_CODE', 'like', "%{$keyword}%")
              ->orWhere('TOOL_NAME', 'like', "%{$keyword}%")
              ->orWhere('TOOL_NAME_KANA', 'like', "%{$keyword}%");
        });
        $searchParts[] = 'キーワード「' . $keyword . '」';
    }

    // 日付検索
    if ($date) {
        $query->whereDate('MOPS_ADD_DATE', $date);
        $searchParts[] = '追加日「' . $date . '」';
    }

    // ソート
    if ($sort === 'date') {
        $query->orderBy('DISPLAY_START_DATE', $order);
    } elseif ($sort === 'code') {
        $query->orderBy('TOOL_CODE', $order);
    } else {
        $query->orderBy('DISPLAY_START_DATE', 'asc')->orderBy('TOOL_CODE', 'asc');
    }

    $tools = $query->paginate($perPage)->appends($request->all());

    // お気に入り判定
    $userId = Auth::id();
    $favoriteCodes = $userId
        ? Favorite::where('USER_ID', $userId)->pluck('TOOL_CODE')->toArray()
        : [];

    foreach ($tools as $tool) {
        $tool->is_favorite = in_array($tool->TOOL_CODE, $favoriteCodes);
    }

    // プルダウン選択用
    $type1s = ToolType1::orderBy('DISP_ORDER')->get();
    $type2s = ToolType2::orderBy('DISP_ORDER')->get();
    $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
        $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
        return [
            'label' => $label,
            'children' => $items,
        ];
    });

    $unitTypes = GeneralClass::where('TYPE_CODE', 'UNIT_TYPE')->orderBy('DISP_ORDER')->get();

    session()->put('last_tool_search_url', $request->fullUrl());

    return view('tools.search', compact(
        'tools',
        'toolTypeOptions',
        'hinmei',
        'unitTypes'
    ))->with('searchLabel', count($searchParts) > 0 ? implode(' × ', $searchParts) : '全件');
}

    public function show($code)
    {
        $tool = Tool::where('TOOL_CODE', $code)
                    ->where('DEL_FLG', 0)
                    ->firstOrFail();

        $userId = Auth::id();
        $tool->is_favorite = $userId
            ? Favorite::where('USER_ID', $userId)
            ->where('TOOL_CODE', $tool->TOOL_CODE)
            ->exists()
            : false;

        return view('tools.show', compact('tool'));
    }

    public function addFavorite(Request $request)
    {
        Favorite::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $request->tool_code]
        );
        return response()->json(['success' => true]);
    }

    public function removeFavorite(Request $request)
    {
        Favorite::where([
            ['USER_ID', '=', Auth::id()],
            ['TOOL_CODE', '=', $request->tool_code]
        ])->delete();
        return response()->json(['success' => true]);
    }
}
