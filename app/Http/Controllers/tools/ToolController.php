<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hinmei;
use App\Models\Tool;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\MToolType1;
use App\Models\MToolType2;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Auth;


class ToolController extends Controller
{

public function search(Request $request)
{
    $toolType2 = $request->query('tool_type2');
    $hinmeiCode = $request->query('hinmei');

    $query = Tool::query();
    $hinmei = null;
    $searchLabel = null;

    if ($hinmeiCode) {
        $query->where('HINMEI', $hinmeiCode);
        $hinmei = Hinmei::where('HINMEI_CODE', $hinmeiCode)->first();
        $searchLabel = optional($hinmei)->HINMEI_NAME ?? '未定義の品名';
    } elseif ($toolType2) {
        $query->where('TOOL_TYPE2', $toolType2);
        $toolType = MToolType2::where('TOOL_TYPE2', $toolType2)->first();
        $searchLabel = optional($toolType)->TOOL_TYPE2_NAME ?? '未定義のツール区分';

        $hinmei = new Fluent([
        'HINMEI_CODE' => null,
        'HINMEI_NAME' => $searchLabel,
    ]);
    }

    $tools = $query->paginate(10);

    $userId = Auth::id();
    $favoriteCodes = $userId
        ? Favorite::where('USER_ID', $userId)->pluck('TOOL_CODE')->toArray()
        : [];

    foreach ($tools as $tool) {
        $tool->is_favorite = in_array($tool->TOOL_CODE, $favoriteCodes);
    }

    // プルダウン用
    $type1s = MToolType1::orderBy('DISPLAY_TURN')->get();
    $type2s = MToolType2::orderBy('DISPLAY_TURN')->get();
    $toolTypeOptions = $type2s->groupBy('TOOL_TYPE1')->map(function ($items, $type1Id) use ($type1s) {
        $label = optional($type1s->firstWhere('TOOL_TYPE1', $type1Id))->TOOL_TYPE1_NAME ?? '未定義';
        return [
            'label' => $label,
            'children' => $items,
        ];
    });

return view('tools.search', compact('tools', 'toolTypeOptions', 'hinmei', 'searchLabel'));
}

public function show($code)
    {
        $tool = Tool::where('TOOL_CODE', $code)->firstOrFail();

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
