<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth; // 認証用ファサード
use App\Models\User; // ユーザーモデルをインポート
use App\Models\Order; // 注文モデルをインポート（将来のDB用モデル）
use App\Models\OrderMeisai; // 注文明細モデルをインポート（将来のDB用モデル）

class ManagementOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($this->hasSearchConditions($request)) {
            return $this->search($request);
        }

        // デフォルト一覧表示
        $query = Order::query()->with(['details.tool']);

        $sort = $request->input('sort', 'CREATE_DT');
        $order = $request->input('order', 'desc');

        $query->orderBy($sort, $order);

        $orders = $query->paginate(15)->appends($request->all());

        return view('manage.managementorder.index', compact('orders', 'sort', 'order'));
    }


    private function hasSearchConditions(Request $request)
    {
        $searchKeys = [
            'ORDER_CODE', 'TOOL_CODE', 'TOOL_NAME', 'ORDER_STATUS',
            'SOSHIKI1', 'SOSHIKI2', 'ORDER_NAME', 'USER_ID', 'CREATE_DT', 'UPDATE_DT'
        ];

        foreach ($searchKeys as $key) {
            if ($request->filled($key)) {
                return true;
            }
        }

        return false;
    }


        private function search(Request $request)
    {
        $query = Order::query()->with(['details.tool']);

        if ($request->filled('ORDER_CODE')) {
            $query->where('ORDER_CODE', 'like', '%' . trim($request->input('ORDER_CODE')) . '%');
        }
        if ($request->filled('TOOL_CODE')) {
            $query->whereHas('details.tool', function ($q) use ($request) {
                $q->where('TOOL_CODE', 'like', '%' . trim($request->input('TOOL_CODE')) . '%');
            });
        }
        if ($request->filled('TOOL_NAME')) {
            $query->whereHas('details.tool', function ($q) use ($request) {
                $q->where('TOOL_NAME', 'like', '%' . trim($request->input('TOOL_NAME')) . '%');
            });
        }
        if ($request->filled('ORDER_STATUS')) {
            $query->where('ORDER_STATUS', trim($request->input('ORDER_STATUS')));
        }
        if ($request->filled('SOSHIKI1')) {
            $query->where('SOSHIKI1', trim($request->input('SOSHIKI1')));
        }
        if ($request->filled('SOSHIKI2')) {
            $query->where('SOSHIKI2', trim($request->input('SOSHIKI2')));
        }
        if ($request->filled('ORDER_NAME')) {
            $query->where('ORDER_NAME', 'like', '%' . trim($request->input('ORDER_NAME')) . '%');
        }
        if ($request->filled('USER_ID')) {
            $query->where('USER_ID', 'like', '%' . trim($request->input('USER_ID')) . '%');
        }
        if ($request->filled('CREATE_DT')) {
            $query->whereDate('CREATE_DT', '>=', $request->input('CREATE_DT'));
        }
        if ($request->filled('UPDATE_DT')) {
            $query->whereDate('CREATE_DT', '<=', $request->input('UPDATE_DT'));
        }

        $sort = $request->input('sort', 'CREATE_DT');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);

        $orders = $query->paginate(15)->appends($request->all());

        return view('manage.managementorder.index', compact('orders', 'sort', 'order'));
    }




    public function show($id)
    {
        // 注文情報取得
        $order = DB::table('ORDER')->where('ORDER_CODE', $id)->first();

        // 注文詳細情報取得
        $tools = DB::table('ORDER_MEISAI')->where('ORDER_CODE', $id)->get();

        // 注文者の支店・部／営業所コード取得
        $user = DB::table('USERS')
            ->where('USER_ID', $order->USER_ID)
            ->first();

        // 支店・部名取得
        $branchName = DB::table('SOSHIKI1')
            ->where('SHITEN_BU_CODE', $user->SHITEN_BU_CODE)
            ->value('SOSHIKI1_NAME');

        // 営業所・グループ名取得
        $officeName = DB::table('SOSHIKI2')
            ->where('EIGYOSHO_GROUP_CODE', $user->EIGYOSHO_GROUP_CODE)
            ->value('SOSHIKI2_NAME');

        return view('manage.managementorder.show', compact('order', 'tools', 'branchName', 'officeName', 'user'));
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
            'UPDATE_APP' => 'Mops',
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
