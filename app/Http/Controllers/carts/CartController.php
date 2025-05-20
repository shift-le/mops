<?php

namespace App\Http\Controllers\carts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
    $userId = Auth::id();

    // カート + 関連ツール情報を取得
    $cartItems = Cart::with('tool')
        ->where('USER_ID', $userId)
        ->get()
        ->filter(fn($cart) => $cart->tool !== null)
        ->map(function ($cart) {
            return [
                'tool' => $cart->tool,
                'quantity' => $cart->QUANTITY,
                'subtotal' => $cart->QUANTITY * ($cart->tool->PRICE ?? 0),
            ];
        });

    $total = $cartItems->sum('subtotal');

    return view('carts.index', compact('cartItems', 'total'));
    }

    public function updateQuantity(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('tool_code');
        $quantity = intval($request->input('quantity'));

        Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->update(['QUANTITY' => $quantity]);

        return redirect()->route('carts.index');
    }

    public function remove(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('tool_code');

        Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->delete();

        return redirect()->route('carts.index');
    }
}
