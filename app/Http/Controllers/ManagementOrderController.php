<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementOrderController extends Controller
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

        return view('manage.managementorder.index', compact('orders', 'keyword'));
    }

    public function show($id)
    {
        $order = DB::table('ORDER')->where('ORDER_CODE', $id)->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $tools = DB::table('ORDER_MEISAI')
                    ->where('ORDER_CODE', $id)
                    ->get();

        return view('manage.managementorder.show', compact('order', 'tools'));
    }


    // 更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'ORDER_STATUS' => 'required|string|max:1',
            'ORDER_NAME' => 'required|string|max:32',
            'ORDER_ADDRESS' => 'nullable|string|max:128',
            'ORDER_PHONE' => 'required|string|max:32',
        ]);

        DB::table('ORDER')->where('ORDER_CODE', $id)->update([
            'ORDER_STATUS' => $request->input('ORDER_STATUS'),
            'ORDER_NAME' => $request->input('ORDER_NAME'),
            'ORDER_ADDRESS' => $request->input('ORDER_ADDRESS'),
            'ORDER_PHONE' => $request->input('ORDER_PHONE'),
            'UPDATE_DT' => now(),
            'UPDATE_APP' => 'WebForm',
            'UPDATE_USER' => 'current_user'
        ]);

        return redirect()->route('managementorder.index')->with('success', '注文情報を更新しました。');
    }

    // 削除
    public function delete($id)
    {
        DB::table('ORDER')->where('ORDER_CODE', $id)->delete();

        return redirect()->route('managementorder.index')->with('success', '注文情報を削除しました。');
    }
}
