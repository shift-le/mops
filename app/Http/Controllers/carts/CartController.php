<?php

namespace App\Http\Controllers\carts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Soshiki2;
use App\Models\Thuzaiin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();

        $cartItems = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->get()
            ->filter(fn($cart) => $cart->tool !== null)
            ->map(function ($cart) {
                return [
                    'tool' => $cart->tool,
                    'quantity' => $cart->QUANTITY,
                    'subtotal' => $cart->QUANTITY * ($cart->tool->TANKA ?? 0),
                ];
            });

        $total = $cartItems->sum('subtotal');

        return view('carts.index', compact('cartItems', 'total'));
    }


    public function addToCart(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();
        $toolCode = $request->input('tool_code');
        $quantity = intval($request->input('quantity', 1));

        if ($quantity < 1) {
            return back()->withErrors(['quantity' => '数量は1以上で指定してください。']);
        }

        $cart = Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->first();

        if ($cart) {

            Cart::where('USER_ID', $userId)
                ->where('TOOL_CODE', $toolCode)
                ->update([
                    'QUANTITY' => $cart->QUANTITY + $quantity,
                    'UPDATE_DT' => now(),
                    'UPDATE_APP' => 'web',
                    'UPDATE_USER' => $userId,
                ]);
        } else {
            Cart::create([
                'USER_ID' => $userId,
                'TOOL_CODE' => $toolCode,
                'QUANTITY' => $quantity,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'web',
                'CREATE_USER' => $userId,
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'web',
                'UPDATE_USER' => $userId,
            ]);
        }

        // モーダル用
        $tool = Tool::where('TOOL_CODE', $toolCode)->first();
        session()->flash('cart_added_tool', $tool);
        session()->flash('cart_added_quantity', $quantity);

        return back();
    }
    public function updateQuantity(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

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

        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();
        $toolCode = $request->input('tool_code');

        Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->delete();

        return redirect()->route('carts.index');
    }

    public function cancelAll(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();
        Cart::where('USER_ID', $userId)->delete();
        return redirect()->route('carts.index');
    }

    public function checkout(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();
        $cartCount = Cart::where('USER_ID', $userId)->count();
        if ($cartCount === 0) {
            return redirect()->route('carts.index')->with('error', 'カートにツールがありません。');
        }

        if ($request->isMethod('post') && $request->has('reset')) {
            session()->forget('checkout_input');
            return redirect()->route('carts.checkout');
        }

        $user = Auth::user();
        $soshiki1 = $user->soshiki1;
        $soshiki2 = $user->soshiki2;
        $soshiki2List = Soshiki2::pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE');
        $userList = [$user->USER_ID => $user->NAME];

        $input = session('checkout_input', []);
        if ($request->isMethod('post')) {

            $input = $request->all();

            session(['checkout_input' => $input]);
        }

        $selected = $input['delivery_select'] ?? $request->input('delivery_select', 'user_' . $user->USER_ID);
        $delivery_name = $input['delivery_name'] ?? '';
        $delivery_data = [];

        // 駐在先を選択した場合の処理
        if (strpos($selected, 'user_') === 0) {
            $userId = substr($selected, 5);

            $thuzaiin = Thuzaiin::where('USER_ID', $userId)->first();
            $userModel = User::where('USER_ID', $userId)->first();
            $delivery_name = $thuzaiin->DELI_NAME ?? ($userList[$userId] ?? '');
            $delivery_data = $thuzaiin ? $thuzaiin->toArray() : [];
            $delivery_data['prefecture'] = $thuzaiin && $thuzaiin->prefecture ? $thuzaiin->prefecture->toArray() : [];

            if ($userModel) {
                $delivery_data['MOBILE_TEL'] = $userModel->MOBILE_TEL ?? '';
                $delivery_data['EMAIL'] = $userModel->EMAIL ?? '';
                $delivery_data['MOBILE_EMAIL'] = $userModel->MOBILE_EMAIL ?? '';
            }
            // 事業所を選択した場合の処理
        } else {
            $code = substr($selected, 9);
            $soshiki2_selected = Soshiki2::where('EIGYOSHO_GROUP_CODE', $code)->first();
            $delivery_name = $soshiki2_selected->SOSHIKI2_NAME ?? '';

            $userModel = User::where('USER_ID', $user->USER_ID)->first();
            if ($userModel) {
                $delivery_name = $userModel->NAME;
            }

            $delivery_data = $soshiki2_selected ? $soshiki2_selected->toArray() : [];
            $delivery_data['prefecture'] = $soshiki2_selected && $soshiki2_selected->prefecture ? $soshiki2_selected->prefecture->toArray() : [];
        }

        return view('carts.checkout', compact(
            'user',
            'soshiki1',
            'soshiki2',
            'soshiki2List',
            'userList',
            'selected',
            'delivery_name',
            'delivery_data'
        ));
    }

    public function confirm(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $soshiki1 = $user->soshiki1;
        $soshiki2 = $user->soshiki2;

        $request->validate([
            'delivery_name' => ['required', 'string', 'max:50'],
            'NOTE' => ['nullable', 'string', 'max:200'],
        ], [
            'delivery_name.required' => '届け先名称は必須です。',
            'delivery_name.max' => '届け先名称は50文字以内で入力してください。',
            'NOTE.max' => '備考は200文字以内で入力してください。',
        ]);

        // 届け先情報
        $delivery_name = $request->input('delivery_name', '');
        $delivery_data = $request->all();

        $delivery_address = '〒' . ($delivery_data['POST_CODE1'] ?? '') . '-' . ($delivery_data['POST_CODE2'] ?? '') .
            ' ' . ($delivery_data['ADDRESS1'] ?? '') .
            ' ' . ($delivery_data['ADDRESS2'] ?? '') .
            ' ' . ($delivery_data['ADDRESS3'] ?? '');

        $delivery_tel = $delivery_data['TEL'] ?? '';

        // カート情報
        $userId = $user->USER_ID;
        $cartItems = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->orderBy('CREATE_DT', 'desc')
            ->get()
            ->filter(fn($cart) => $cart->tool !== null)
            ->map(function ($cart) {
                return [
                    'tool' => $cart->tool,
                    'quantity' => $cart->QUANTITY,
                    'subtotal' => $cart->QUANTITY * ($cart->tool->TANKA ?? 0),
                ];
            });
        $total = $cartItems->sum('subtotal');

        session([
            'checkout_input' => $request->all(),
        ]);

        return view('carts.confirm', compact(
            'user',
            'soshiki1',
            'soshiki2',
            'delivery_name',
            'delivery_data',
            'delivery_address',
            'delivery_tel',
            'cartItems',
            'total'
        ));
    }

    public function complete(Request $request)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userId = $user->USER_ID;

        $cartCheck = $request->input('cart_check', []);

        // 現在のDB上のカート内容を取得
        $cartItems = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->get()
            ->filter(fn($cart) => $cart->tool !== null);

        if ($cartItems->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'カートが空です。');
        }

        $userId = Auth::id();
        $cartCheck = $request->input('cart_check', []);

        // 現在のDB上のカート内容を取得
        $dbCart = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->get()
            ->filter(fn($cart) => $cart->tool !== null)
            ->mapWithKeys(function ($cart) {
                return [
                    $cart->TOOL_CODE => [
                        'quantity' => $cart->QUANTITY,
                        'tanka' => $cart->tool->TANKA ?? 0,
                    ]
                ];
            })->toArray();

        // 排他チェック
        if ($cartCheck != $dbCart) {
            return redirect()->route('carts.index')->with('error', 'カート内容が変更されています。再度ご確認ください。');
        }

        // 合計金額計算
        $total = 0;
        foreach ($dbCart as $item) {
            $total += $item['quantity'] * $item['tanka'];
        }

        // 届け先メールアドレス
        $deliveryEmail = $request->input('EMAIL', '');
        $delivery_name = $request->input('delivery_name', '');
        $delivery_address = '〒' . ($request->POST_CODE1 ?? '') . '-' . ($request->POST_CODE2 ?? '') . ' ' .
            ($request->ADDRESS1 ?? '') . ' ' .
            ($request->ADDRESS2 ?? '') . ' ' .
            ($request->ADDRESS3 ?? '');
        $delivery_tel = $request->input('TEL', '');

        // 注文コード（例：ORD20250605123001）
        $orderCode = 'ORD' . now()->format('YmdHis');

        DB::transaction(function () use ($orderCode, $user, $cartItems, $delivery_name, $delivery_address, $delivery_tel) {
            // ORDER テーブルに登録
            DB::table('ORDER')->updateOrInsert(
                [
                    'ORDER_CODE' => $orderCode,
                ],
                [
                    'ORDER_STATUS' => '1',
                    'HASSOUSAKI_CODE' => 'HS001', // 仮コード
                    'USER_ID' => $user->USER_ID,
                    'IRAI_NAME' => $user->NAME,
                    'ORDER_NAME' => $delivery_name,
                    'ORDER_ADDRESS' => $delivery_address,
                    'ORDER_PHONE' => $delivery_tel,
                    'ORDER_STATUS2' => '0',
                    'ORDER_TOOLID' => $cartItems->first()->TOOL_CODE ?? '',
                    'AMOUNT' => $cartItems->sum('QUANTITY'),
                    'SUBTOTAL' => $cartItems->sum(fn($item) => $item->QUANTITY * ($item->tool->TANKA ?? 0)),
                    'DEL_FLG' => 0,
                    'CREATE_DT' => now(),
                    'CREATE_APP' => 'web',
                    'CREATE_USER' => $user->USER_ID,
                    'UPDATE_DT' => now(),
                    'UPDATE_APP' => 'web',
                    'UPDATE_USER' => $user->USER_ID,
                ]
            );

            // カートを空にする
            Cart::where('USER_ID', $user->USER_ID)->delete();
        });

        // セッション削除
        session()->forget('checkout_input');

        // 完了画面に遷移
        return view('carts.complete', [
            'user' => $user,
            'total' => $total,
            'deliveryEmail' => $deliveryEmail,
        ]);
    }
}
