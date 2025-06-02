<?php

namespace App\Http\Controllers\tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hinmei;
use App\Models\Tool;
use App\Models\Cart;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;


class ToolController extends Controller
{

    public function search(Request $request)
    {
        $hinmeiCode = $request->query('hinmei');
        $sort = $request->query('sort');
        $order = $request->query('order', 'asc');

        // 品名取得（存在しなければ404）
        $hinmei = Hinmei::where('HINMEI_CODE', $hinmeiCode)->firstOrFail();

        // 並び替え条件付きでツールを取得
        $query = Tool::where('HINMEI', $hinmeiCode);

        if ($sort === 'date') {
            $query->orderBy('DISPLAY_START_DATE', $order);
        } elseif ($sort === 'code') {
            $query->orderBy('TOOL_CODE', $order);
        } else {
            $query->orderBy('TOOL_CODE', 'asc'); // デフォルト
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
        public function show($code)
        {
            $tool = Tool::where('TOOL_CODE', $code)->firstOrFail();
            return view('tools.show', compact('tool'));
        }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('carts.index')->with('error', 'ログインしてください');
        }

        $toolCode = $request->input('tool_code');
        $quantity = (int) $request->input('quantity');

        Cart::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $toolCode],
            [
                'QUANTITY' => $quantity,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'WebUI',
                'CREATE_USER' => Auth::id(),
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'WebUI',
                'UPDATE_USER' => Auth::id(),
            ]
        );

        $tool = Tool::where('TOOL_CODE', $toolCode)->first();
        session()->flash('cart_added_tool', $tool);
        session()->flash('cart_added_quantity', $quantity);

        return back();
    }

    public function addFavorite(Request $request) {
        Favorite::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $request->tool_code]
        );
        return response()->json(['success' => true]);
    }

    public function removeFavorite(Request $request) {
        Favorite::where([
            ['USER_ID', '=', Auth::id()],
            ['TOOL_CODE', '=', $request->tool_code]
        ])->delete();
        return response()->json(['success' => true]);
    }
}
