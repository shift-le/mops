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
use App\Models\GeneralClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderCompletedUser;
use App\Notifications\OrderCompletedAdmin;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $cartItems = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->get()
            ->filter(fn($cart) => $cart->tool !== null)
            ->map(function ($cart) {
                $unit = \App\Models\GeneralClass::where('TYPE_CODE', 'UNIT_TYPE')
                    ->where('KEY', $cart->tool->UNIT_TYPE)
                    ->first();

                return [
                    'tool' => $cart->tool,
                    'QUANTITY' => $cart->QUANTITY,
                    'unit' => $unit ? $unit->VALUE : '',
                    'subtotal' => $cart->QUANTITY * ($cart->tool->TANKA ?? 0),
                ];
            });

        $total = $cartItems->sum('subtotal');

        return view('carts.index', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('TOOL_CODE');
        $quantity = intval($request->input('QUANTITY', 1));

        if ($quantity < 1) {
            return back()->withErrors(['QUANTITY' => '数量は1以上で指定してください。']);
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

        $tool = Tool::where('TOOL_CODE', $toolCode)->first();
        session()->flash('cart_added_tool', $tool);
        session()->flash('cart_added_quantity', $quantity);

        return back();
    }

    public function updateQuantity(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('TOOL_CODE');
        $quantity = intval($request->input('QUANTITY'));

        Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->update(['QUANTITY' => $quantity]);

        return redirect()->route('carts.index');
    }

    public function remove(Request $request)
    {
        $userId = Auth::id();
        $toolCode = $request->input('TOOL_CODE');

        Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->delete();

        return redirect()->route('carts.index');
    }

    public function cancelAll(Request $request)
    {
        $userId = Auth::id();
        Cart::where('USER_ID', $userId)->delete();
        return redirect()->route('carts.index');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $userId = $user->USER_ID;

        if (Cart::where('USER_ID', $userId)->count() === 0) {
            return redirect()->route('carts.index')->with('error', 'カートにツールがありません。');
        }

        $soshiki1 = $user->soshiki1;
        $soshiki2 = $user->soshiki2;

        $userGroupCode = $user->EIGYOSHO_GROUP_CODE;

        // 所属先候補一覧
        $soshiki2List = Soshiki2::where('EIGYOSHO_GROUP_CODE', $userGroupCode)
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE');

        // 自分だけを含む駐在員候補（必要に応じて複数追加）
        $userList = [$user->USER_ID => $user->NAME];

        // 駐在員かどうかのフラグ
        $showThuzaiin = Thuzaiin::where('USER_ID', $user->USER_ID)->exists();

        // リセット処理
        if ($request->has('reset') || $request->has('RESET')) {
            session()->forget('checkout_input');
            return redirect()->route('carts.checkout');
        }

        // 初回アクセスなら初期値をセッションに保存
        if (!$request->isMethod('post') && !session()->has('checkout_input')) {
            $default_selected = $showThuzaiin
                ? 'user_' . $user->USER_ID
                : 'soshiki2_' . $user->soshiki2->EIGYOSHO_CODE;

            session(['checkout_input' => ['DELIVERY_SELECT' => $default_selected]]);
        }

        // POSTされたらセッション更新
        if ($request->isMethod('post')) {
            session(['checkout_input' => $request->all()]);
        }

        // セッションから選択値取得
        $input = session('checkout_input', []);
        $selected = $input['DELIVERY_SELECT'] ?? ($showThuzaiin ? 'user_' . $user->USER_ID : 'soshiki2_' . $user->soshiki2->EIGYOSHO_CODE);

        // 届け先名と情報
        $delivery_name = '';
        $delivery_data = [];

        if (str_starts_with($selected, 'user_')) {
            $selectUserId = substr($selected, 5);
            $thuzaiin = Thuzaiin::where('USER_ID', $selectUserId)->first();
            $userModel = User::find($selectUserId);
            $delivery_name = $thuzaiin->DELI_NAME ?? ($userModel->NAME ?? '');
            $delivery_data = $thuzaiin ? $thuzaiin->toArray() : [];
            $delivery_data['prefecture'] = $thuzaiin?->prefecture?->toArray() ?? [];

            if ($userModel) {
                $delivery_data['MOBILE_TEL'] = $userModel->MOBILE_TEL ?? '';
                $delivery_data['EMAIL'] = $userModel->EMAIL ?? '';
                $delivery_data['MOBILE_EMAIL'] = $userModel->MOBILE_EMAIL ?? '';
            }
        } elseif (str_starts_with($selected, 'soshiki2_')) {
            $delivery_name = $user->NAME;
            $delivery_data = $soshiki2->toArray();
            $delivery_data['prefecture'] = $soshiki2->prefecture?->toArray() ?? [];
            $delivery_data['MOBILE_TEL'] = $user->MOBILE_TEL ?? '';
            $delivery_data['EMAIL'] = $user->EMAIL ?? '';
            $delivery_data['MOBILE_EMAIL'] = $user->MOBILE_EMAIL ?? '';
        }

        // 都道府県名を取得
        if ($delivery_data && isset($delivery_data['PREFECTURE'])) {
            $prefCode = $delivery_data['PREFECTURE'];
            $prefRecord = GeneralClass::where('TYPE_CODE', 'PREFECTURE')
                ->where('KEY', $prefCode)
                ->first();
            $delivery_data['PREFECTURE_NAME'] = $prefRecord?->VALUE ?? '';
        }

        return view('carts.checkout', compact(
            'user',
            'soshiki1',
            'soshiki2',
            'soshiki2List',
            'userList',
            'selected',
            'delivery_name',
            'delivery_data',
            'showThuzaiin'
        ));
    }

    public function confirm(Request $request)
    {
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

        $delivery_name = $request->input('delivery_name', '');
        $delivery_data = $request->all();

        $delivery_address = '〒' . ($delivery_data['POST_CODE1'] ?? '') . '-' . ($delivery_data['POST_CODE2'] ?? '') .
            ' ' . ($delivery_data['ADDRESS1'] ?? '') .
            ' ' . ($delivery_data['ADDRESS2'] ?? '') .
            ' ' . ($delivery_data['ADDRESS3'] ?? '');

        $delivery_tel = $delivery_data['TEL'] ?? '';

        $userId = $user->USER_ID;
        $cartItems = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->orderBy('CREATE_DT', 'desc')
            ->get()
            ->filter(fn($cart) => $cart->tool !== null)
            ->map(function ($cart) {
                $unit = GeneralClass::where('TYPE_CODE', 'UNIT_TYPE')
                    ->where('KEY', $cart->tool->UNIT_TYPE)
                    ->first();

                return [
                    'tool' => $cart->tool,
                    'QUANTITY' => $cart->QUANTITY,
                    'unit' => $unit ? $unit->VALUE : '',
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('soshiki1', 'soshiki2');
        $userId = $user->USER_ID;

        $cartCheck = $request->input('cart_check', []);

        $cartItemsRaw = Cart::with('tool')
            ->where('USER_ID', $userId)
            ->get()
            ->filter(fn($cart) => $cart->tool !== null);

        if ($cartItemsRaw->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'カートが空です。');
        }

        $dbCart = $cartItemsRaw->mapWithKeys(function ($cart) {
            return [
                $cart->TOOL_CODE => [
                    'QUANTITY' => $cart->QUANTITY,
                    'tanka' => $cart->tool->TANKA ?? 0,
                ]
            ];
        })->toArray();

        $clientQuantities = collect($cartCheck)->map(fn($v) => (int) $v['QUANTITY']);
        $dbQuantities = collect($dbCart)->map(fn($v) => (int) $v['QUANTITY']);

        if ($clientQuantities != $dbQuantities) {
            return redirect()->route('carts.index')->with('error', 'カート内容が変更されています。再度ご確認ください。');
        }

        $deliveryEmail = $request->input('EMAIL', '');
        $input = session('checkout_input', []);
        $delivery_name = $input['delivery_name'] ?? '';
        $postcode = $input['POST_CODE'] ?? '';
        if (preg_match('/^\d{7}$/', $postcode)) {
            $postcode = substr($postcode, 0, 3) . '-' . substr($postcode, 3);
        }

        $delivery_address = '〒' . $postcode . ' ' .
            ($input['ADDRESS1'] ?? '') . ' ' .
            ($input['ADDRESS2'] ?? '') . ' ' .
            ($input['ADDRESS3'] ?? '');
        $delivery_tel = $input['TEL'] ?? '';
        $note = $input['NOTE'] ?? '';

        // ▼ 依頼主（発注元）住所に郵便番号を付ける
        $orgPostCode = $user->soshiki1->POST_CODE ?? '';
        if (preg_match('/^\d{7}$/', $orgPostCode)) {
            $orgPostCode = substr($orgPostCode, 0, 3) . '-' . substr($orgPostCode, 3);
        }

        $order_address = '〒' . $orgPostCode . ' ' .
            ($user->soshiki1->ADDRESS1 ?? '') . ' ' .
            ($user->soshiki1->ADDRESS2 ?? '') . ' ' .
            ($user->soshiki1->ADDRESS3 ?? '');

        $order_tel = $user->soshiki1->TEL ?? '';
        $order_name = $user->soshiki1->SOSHIKI1_NAME . ' ' . $user->soshiki2->SOSHIKI2_NAME . ' ' . $user->NAME;

        $orderCode = 'ORD' . now()->format('YmdHis');

        DB::transaction(function () use (
            $orderCode,
            $user,
            $cartItemsRaw,
            $delivery_name,
            $delivery_address,
            $delivery_tel,
            $note,
            $order_name,
            $order_address,
            $order_tel
        ) {
            DB::table('ORDER')->insert([
                'ORDER_CODE' => $orderCode,
                'USER_ID' => $user->USER_ID,
                'ORDER_STATUS' => '1',
                'HASSOUSAKI_CODE' => '0',
                'ORDER_STATUS2' => '0',
                'ORDER_TOOLID' => '0',
                'AMOUNT' => $cartItemsRaw->sum(fn($item) => $item->QUANTITY * ($item->tool->TANKA ?? 0)),
                'SUBTOTAL' => $cartItemsRaw->sum(fn($item) => $item->QUANTITY * ($item->tool->TANKA ?? 0)),
                'IRAI_NAME' => $order_name,
                'ORDER_NAME' => $user->NAME,
                'ORDER_ADDRESS' => $order_address,
                'ORDER_PHONE' => $order_tel,
                'DELI_NAME' => $delivery_name,
                'DELI_ADDRESS' => $delivery_address,
                'DELI_PHONE' => $delivery_tel,
                'NOTE' => $note,
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'web',
                'CREATE_USER' => $user->USER_ID,
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'web',
                'UPDATE_USER' => $user->USER_ID,
            ]);

            foreach ($cartItemsRaw as $item) {
                DB::table('ORDER_MEISAI')->insert([
                    'ORDER_CODE' => $orderCode,
                    'TOOL_CODE' => $item->TOOL_CODE,
                    'USER_ID' => $user->USER_ID,
                    'IRAI_NAME' => $order_name,
                    'ORDER_NAME' => $user->NAME,
                    'ORDER_ADDRESS' => $order_address,
                    'ORDER_STATUS' => '1',
                    'TOOLID' => 0,
                    'AMOUNT' => $item->QUANTITY,
                    'TOOL_QUANTITY' => $item->QUANTITY,
                    'TOOL_NAME' => $item->tool->TOOL_NAME,
                    'TANKA' => $item->tool->TANKA,
                    'QUANTITY' => $item->QUANTITY,
                    'SUBTOTAL' => $item->QUANTITY * ($item->tool->TANKA ?? 0),
                    'DEL_FLG' => 0,
                    'CREATE_DT' => now(),
                    'CREATE_APP' => 'web',
                    'CREATE_USER' => $user->USER_ID,
                    'UPDATE_DT' => now(),
                    'UPDATE_APP' => 'web',
                    'UPDATE_USER' => $user->USER_ID,
                ]);
            }

            Cart::where('USER_ID', $user->USER_ID)->delete();
        });

        $cartDetails = $cartItemsRaw->map(function ($item) {
            $unit = GeneralClass::where('TYPE_CODE', 'UNIT_TYPE')
                ->where('KEY', $item->tool->UNIT_TYPE)
                ->value('VALUE') ?? '';

            return [
                'tool' => $item->tool,
                'QUANTITY' => $item->QUANTITY,
                'unit' => $unit,
                'subtotal' => $item->QUANTITY * ($item->tool->TANKA ?? 0),
            ];
        });

        $total = $cartDetails->sum('subtotal');

        Notification::route('mail', $user->EMAIL)
            ->notify(new OrderCompletedUser(
                $orderCode,
                now()->format('Y/m/d H:i'),
                $user->NAME,
                $delivery_name,
                $delivery_address,
                $delivery_tel,
                $cartDetails,
                $total
            ));

        Notification::route('mail', ['Letroaling3@gmail.com'])
            ->notify(new OrderCompletedAdmin(
                $orderCode,
                now()->format('Y/m/d H:i'),
                $user->soshiki1->SOSHIKI1_NAME . ' ' . $user->soshiki2->SOSHIKI2_NAME,
                $user->NAME,
                $user->soshiki1->ADDRESS1 . ' ' . $user->soshiki1->ADDRESS2 . ' ' . $user->soshiki1->ADDRESS3,
                $user->soshiki1->TEL,
                $delivery_name,
                $delivery_address,
                $delivery_tel,
                $note,
                $cartDetails,
                $total
            ));

        session()->forget('checkout_input');

        return view('carts.complete', [
            'user' => $user,
            'total' => $total,
            'deliveryEmail' => $deliveryEmail,
        ]);
    }
}
