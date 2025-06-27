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
        $userId = Auth::user()->USER_ID;

        $query = DB::table('ORDER_MEISAI as meisai')
            ->join('ORDER as ord', 'meisai.ORDER_CODE', '=', 'ord.ORDER_CODE')
            ->join('TOOL as tool', 'meisai.TOOL_CODE', '=', 'tool.TOOL_CODE')
            ->leftJoin('M_GENERAL_TYPE as unit', function ($join) {
                $join->on('tool.UNIT_TYPE', '=', 'unit.KEY')
                    ->where('unit.TYPE_CODE', '=', 'UNIT_TYPE');
            })
            ->select(
                'ord.ORDER_CODE',
                'ord.CREATE_DT',
                'meisai.TOOL_CODE as ORDER_TOOLID',
                'meisai.TOOL_NAME',
                'meisai.QUANTITY',
                'meisai.ORDER_STATUS',
                'unit.VALUE as UNIT_NAME'
            )
            ->where('ord.DEL_FLG', 0)
            ->where('meisai.DEL_FLG', 0)
            ->where('ord.USER_ID', $userId);

        if ($request->filled('order_id')) {
            $orderIdInput = $request->input('order_id');

            $query->where(function ($q) use ($orderIdInput) {
                $q->where('ord.ORDER_CODE', $orderIdInput)
                    ->orWhere('ord.ORDER_CODE', 'like', '%' . $orderIdInput . '%');
            });
        }

        if ($request->filled('TOOL_CODE')) {
            $toolCodeInput = $request->input('TOOL_CODE');

            $query->where(function ($q) use ($toolCodeInput) {
                $q->where('meisai.TOOL_CODE', $toolCodeInput)
                    ->orWhere('meisai.TOOL_CODE', 'like', '%' . $toolCodeInput . '%');
            });
        }

        if ($request->filled('tool_name')) {
            $query->where('meisai.TOOL_NAME', 'like', '%' . $request->tool_name . '%');
        }

        if ($request->filled('order_status')) {
            if ($request->order_status === '印刷作業中') {
                $query->where('meisai.ORDER_STATUS', '1');
            } elseif ($request->order_status === '出荷済') {
                $query->where('meisai.ORDER_STATUS', '0');
            }
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
            $query->whereDate('ord.CREATE_DT', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('ord.CREATE_DT', '<=', $end_date);
        }

        $orders = $query->orderBy('ord.CREATE_DT', 'desc')->paginate(15)->appends($request->all());
        session()->put('ordhistory_query', $request->query());

        $matchedOrderCodes = $query->distinct()->pluck('ord.ORDER_CODE');

        // ページネーション（注文ID単位）
        $paginatedOrders = DB::table('ORDER')
            ->whereIn('ORDER_CODE', $matchedOrderCodes)
            ->orderBy('CREATE_DT', 'desc')
            ->paginate(15)
            ->appends($request->all());

        // 表示する全明細（ヒット注文IDに含まれる全明細）
        $fullQuery = DB::table('ORDER_MEISAI as meisai')
            ->join('ORDER as ord', 'meisai.ORDER_CODE', '=', 'ord.ORDER_CODE')
            ->join('TOOL as tool', 'meisai.TOOL_CODE', '=', 'tool.TOOL_CODE')
            ->leftJoin('M_GENERAL_TYPE as unit', function ($join) {
                $join->on('tool.UNIT_TYPE', '=', 'unit.KEY')
                    ->where('unit.TYPE_CODE', '=', 'UNIT_TYPE');
            })
            ->select(
                'ord.ORDER_CODE',
                'ord.CREATE_DT',
                'meisai.TOOL_CODE as ORDER_TOOLID',
                'meisai.TOOL_NAME',
                'meisai.QUANTITY',
                'meisai.ORDER_STATUS',
                'unit.VALUE as UNIT_NAME'
            )
            ->whereIn('ord.ORDER_CODE', $paginatedOrders->pluck('ORDER_CODE'));

        $groupedOrders = $fullQuery->get()->groupBy('ORDER_CODE');


        return view('ordhistory.result', compact('orders', 'groupedOrders'));
    }

    public function show($orderCode)
    {
        $header = DB::table('ORDER')
            ->where('ORDER_CODE', $orderCode)
            ->first();

        if (!$header) {
            abort(404, 'Order not found');
        }

        $details = DB::table('ORDER_MEISAI as meisai')
            ->join('TOOL as tool', 'meisai.TOOL_CODE', '=', 'tool.TOOL_CODE')
            ->leftJoin('M_GENERAL_TYPE as unit', function ($join) {
                $join->on('tool.UNIT_TYPE', '=', 'unit.KEY')
                    ->where('unit.TYPE_CODE', '=', 'UNIT_TYPE');
            })
            ->where('meisai.ORDER_CODE', $orderCode)
            ->orderBy('meisai.CREATE_DT', 'desc')
            ->select(
                'meisai.*',
                'tool.TOOL_CODE',
                'tool.TOOL_NAME',
                'tool.UNIT_TYPE',
                'unit.VALUE as UNIT_NAME',
                'meisai.ORDER_STATUS'
            )
            ->get();

        $orderDate = Carbon::parse($header->CREATE_DT)->format('Y/m/d');

        return view('ordhistory.show', compact('orderCode', 'orderDate', 'header', 'details'));
    }

    public function repeat($orderCode)
    {
        $userId = Auth::id();

        $details = DB::table('ORDER_MEISAI')
            ->select('TOOL_CODE', 'QUANTITY')
            ->where('ORDER_CODE', $orderCode)
            ->where('DEL_FLG', 0)
            ->get();

        foreach ($details as $item) {
            $toolCode = $item->TOOL_CODE;
            $quantity = (int) $item->QUANTITY;

            if ($quantity <= 0 || empty($toolCode)) continue;

            // 有効ツールか確認
            $tool = DB::table('TOOL')
                ->where('TOOL_CODE', $toolCode)
                ->where('DEL_FLG', 0)
                ->whereDate('MOPS_START_DATE', '<=', now()->toDateString())
                ->whereDate('MOPS_END_DATE', '>=', now()->toDateString())
                ->first();

            if (!$tool) continue;

            // addToCartと同じロジックで追加
            $existing = Cart::where('USER_ID', $userId)
                ->where('TOOL_CODE', $toolCode)
                ->first();

            if ($existing) {
                Cart::where('USER_ID', $userId)
                    ->where('TOOL_CODE', $toolCode)
                    ->update([
                        'QUANTITY' => $quantity,
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
        }

        return redirect()->route('carts.index');
    }
}
