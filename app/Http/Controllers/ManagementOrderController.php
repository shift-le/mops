<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ORDER')
            ->select('*');

        if ($request->filled('order_code')) {
            $query->where('ORDER_CODE', 'like', '%' . $request->order_code . '%');
        }

        if ($request->filled('irai_name')) {
            $query->where('IRAI_NAME', 'like', '%' . $request->irai_name . '%');
        }

        if ($request->filled('order_status')) {
            $query->where('ORDER_STATUS', $request->order_status);
        }

        $orders = $query->orderBy('CREATE_DT', 'desc')->paginate(15);

        return view('manage.managementorder.index', compact('orders'));
    }


    // 詳細・編集画面表示
    public function show($id)
    {
        $order = DB::table('ORDER')->where('ORDER_CODE', $id)->first();

        if (!$order) {
            abort(404, 'ORDER not found');
        }

        return view('manage.managementorder.detail', compact('order'));
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
