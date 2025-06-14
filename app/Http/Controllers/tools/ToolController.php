<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hinmei;
use App\Models\Tool;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\ToolType1;
use App\Models\ToolType2;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Auth;


class ToolController extends Controller
{

    public function search(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $hinmeiCode = $request->query('hinmei');
        $sort = $request->query('sort');
        $order = $request->query('order', 'asc');
        $toolType2 = $request->query('tool_type2');

        $query = Tool::query();
        $hinmei = null;
        $searchLabel = null;

        if ($hinmeiCode) {
            $query->where('HINMEI', $hinmeiCode);
            $hinmei = Hinmei::where('HINMEI_CODE', $hinmeiCode)->first();
            $searchLabel = optional($hinmei)->HINMEI_NAME ?? '未定義の品名';
        } elseif ($toolType2) {
            $query->where('TOOL_TYPE2', $toolType2);
            $toolType = ToolType2::where('TOOL_TYPE2', $toolType2)->first();
            $searchLabel = optional($toolType)->TOOL_TYPE2_NAME ?? '未定義のツール区分';

            $hinmei = new Fluent([
                'HINMEI_CODE' => null,
                'HINMEI_NAME' => $searchLabel,
            ]);
            if ($sort === 'date') {
                $query->orderBy('DISPLAY_START_DATE', $order);
            } elseif ($sort === 'code') {
                $query->orderBy('TOOL_CODE', $order);
            } else {
                $query->orderBy('DISPLAY_START_DATE', 'asc')
                    ->orderBy('TOOL_CODE', 'asc');
            }
            $tools = $query->paginate(10);

            // ログインユーザーのお気に入り取得（なければ null）
            $userId = Auth::id();
            if ($userId) {
                $favoriteCodes = Favorite::where('USER_ID', $userId)
                    ->pluck('TOOL_CODE')
                    ->toArray();
            } else {
                $favoriteCodes = [];
            }

            // ツールにお気に入り状態を付与
            foreach ($tools as $tool) {
                $tool->is_favorite = in_array($tool->TOOL_CODE, $favoriteCodes);
            }

            return view('tools.search', compact('hinmei', 'tools'));
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
        $type1s = ToolType1::orderBy('DISP_ORDER')->get();
        $type2s = ToolType2::orderBy('DISP_ORDER')->get();
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

        if (!Auth::check()) {
            return redirect('/login');
        }

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

        if (!Auth::check()) {
            return redirect('/login');
        }

        Favorite::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $request->tool_code]
        );
        return response()->json(['success' => true]);
    }

    public function removeFavorite(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }
        Favorite::where([
            ['USER_ID', '=', Auth::id()],
            ['TOOL_CODE', '=', $request->tool_code]
        ])->delete();
        return response()->json(['success' => true]);
    }
}
