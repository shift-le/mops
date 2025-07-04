<?php

namespace App\Http\Controllers\favorites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hinmei;
use App\Models\Tool;
use App\Models\Cart;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function search(Request $request)
    {
        $sort = $request->query('sort');
        $order = $request->query('order', 'asc');

        $userId = Auth::id();

        // ユーザーのお気に入りツールコード一覧を取得
        $favoriteCodes = Favorite::where('USER_ID', $userId)
            ->pluck('TOOL_CODE')
            ->toArray();

        // お気に入りツールを取得
        $query = Tool::whereIn('TOOL_CODE', $favoriteCodes)
            ->where('DEL_FLG', 0);

        if ($sort === 'date') {
            $query->orderBy('DISPLAY_START_DATE', $order);
        } elseif ($sort === 'code') {
            $query->orderBy('TOOL_CODE', $order);
        } else {
            $query->orderBy('TOOL_CODE', 'asc');
        }

        $tools = $query->paginate(20)->appends($request->all());

        return view('favorites.search', compact('tools'));
    }

    public function show($code)
    {
        $tool = Tool::where('TOOL_CODE', $code)->firstOrFail();
        return view('tools.show', compact('tool'));
    }

    public function addToCart(Request $request)
    {
        Cart::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $request->tool_code],
            ['QUANTITY' => $request->quantity]
        );
        $tool = Tool::where('TOOL_CODE', $request->tool_code)->first();
        session()->flash('cart_added_tool', $tool);
        session()->flash('cart_added_quantity', $request->quantity);

        return redirect()->back();
        return response()->json(['success' => true]);
    }

    public function addFavorite(Request $request)
    {
        Favorite::updateOrCreate(
            ['USER_ID' => Auth::id(), 'TOOL_CODE' => $request->tool_code]
        );
        return redirect()->back()->with('success', 'お気に入りに追加しました');
    }

    public function removeFavorite(Request $request)
    {
        Favorite::where([
            ['USER_ID', '=', Auth::id()],
            ['TOOL_CODE', '=', $request->TOOL_CODE]
        ])->delete();
        return redirect()->back()->with('success', 'お気に入りから削除しました');
    }

    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('TOOL_CODE');

        if (!$userId || !$toolCode) {
            return back()->with('error', '処理できませんでした');
        }

        $exists = Favorite::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->exists();

        if ($exists) {
            Favorite::where('USER_ID', $userId)
                ->where('TOOL_CODE', $toolCode)
                ->delete();
        } else {
            Favorite::create([
                'USER_ID' => $userId,
                'TOOL_CODE' => $toolCode,
            ]);
        }

        return back();
    }
}
