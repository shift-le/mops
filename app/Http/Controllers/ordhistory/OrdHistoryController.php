<?php

namespace App\Http\Controllers\ordhistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cart;

class OrdHistoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = DB::table('ORDER');

        if (!empty($keyword)) {
            $query->where('ORDER_CODE', 'like', "%$keyword%")
                ->orWhere('USER_ID', 'like', "%$keyword%");
        }

        $orders = $query->orderBy('CREATE_DT', 'desc')->paginate(15);

        return view('ordhistory.index', compact('orders', 'keyword'));
    }

        public function result(Request $request)
    {
        $query = DB::table('ORDER')
            ->where('DEL_FLG', 0)
            ->where('USER_ID', Auth::id());

        if ($request->filled('order_id')) {
            $query->where('ORDER_CODE', $request->order_id);
        }

        if ($request->filled('tool_code')) {
            $query->where('ORDER_TOOLID', 'like', '%' . $request->tool_code . '%');
        }

        if ($request->filled('tool_name')) {
            $query->where('ORDER_NAME', 'like', '%' . $request->tool_name . '%');
        }

        if ($request->filled('order_status')) {
            $query->where('ORDER_STATUS', $request->order_status === '注文受付' ? '0' : '1');
        }

        // 日付の変換とバリデーション
        $start_date = $request->start_date 
            ? Carbon::createFromFormat('Y年m月d日', $request->start_date)->format('Y-m-d') 
            : null;

        $end_date = $request->end_date 
            ? Carbon::createFromFormat('Y年m月d日', $request->end_date)->format('Y-m-d') 
            : null;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ], [
            'end_date' => 'nullable|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => '終了日は開始日以降の日付を選択してください。',
        ]);

        if ($validator->fails()) {
            return redirect()->route('ordhistory.index')
                ->withErrors($validator)
                ->withInput();
        }

        if ($start_date) {
            $query->whereDate('CREATE_DT', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('CREATE_DT', '<=', $end_date);
        }

        $orders = $query->orderBy('CREATE_DT', 'desc')->get();
        $groupedOrders = $orders->groupBy('ORDER_CODE');

        return view('ordhistory.result', compact('groupedOrders'));
    }


public function show($orderCode)
{
    $header = DB::table('ORDER')
        ->where('ORDER_CODE', $orderCode)
        ->first();

    if (!$header) {
        abort(404, 'Order not found');
    }

    $details = DB::table('ORDER_MEISAI')
        ->where('ORDER_CODE', $orderCode)
        ->get();

    $orderDate = Carbon::parse($header->CREATE_DT)->format('Y/m/d');

    return view('ordhistory.show', compact('orderCode', 'orderDate', 'header', 'details'));
}
public function repeat($orderCode)
{
    $userId = Auth::id();

    $details = DB::table('ORDER_MEISAI')
        ->where('ORDER_CODE', $orderCode)
        ->get();

    foreach ($details as $item) {
        $toolCode = $item->TOOLID;
        $amount = (int) $item->AMOUNT;

        if ($amount <= 0 || empty($toolCode)) {
            continue;
        }

        $existing = Cart::where('USER_ID', $userId)
            ->where('TOOL_CODE', $toolCode)
            ->first();

        if ($existing) {
            // 各ツールに個別の数量を加算する
            $existing->QUANTITY += $amount;
            $existing->UPDATE_DT = now();
            $existing->UPDATE_APP = 'web';
            $existing->UPDATE_USER = $userId;
            $existing->save();
        } else {
            Cart::create([
                'USER_ID' => $userId,
                'TOOL_CODE' => $toolCode,
                'QUANTITY' => $amount,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'web',
                'CREATE_USER' => $userId,
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'web',
                'UPDATE_USER' => $userId,
            ]);
        }
    }

    return redirect()->route('carts.index')->with('success', '注文内容をカートに追加しました。');
}


}
