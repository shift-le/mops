<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth; // 認証用ファサード
use Illuminate\Support\Facades\Log; // ログ出力用ファサード
use App\Models\User; // ユーザーモデルをインポート
use App\Models\Order; // 注文モデルをインポート（将来のDB用モデル）
use App\Models\OrderMeisai; // 注文明細モデルをインポート（将来のDB用モデル）
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class ManagementOrderController extends Controller
{
    public function index(Request $request)
    {

    //日付バリデーション
    $createDt = $request->input('CREATE_DT');
    $updateDt = $request->input('UPDATE_DT');

    if ($createDt && $updateDt && $updateDt < $createDt) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['date_range' => '終了日は開始日より前の日付にできません。']);
    }

    if ($this->hasSearchConditions($request)) {
        return $this->search($request);
    }

        if ($this->hasSearchConditions($request)) {
            return $this->search($request);
        }

        // デフォルト一覧表示
        $query = Order::query()->with(['details.tool']);

        $sort = $request->input('sort', 'CREATE_DT');
        $order = $request->input('order', 'desc');
        
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();


        $query->orderBy($sort, $order);

        $orders = $query->paginate(15)->appends($request->all());

        // ログ出力
        Log::debug('【管理】注文一覧取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'sort' => $sort,
            'order' => $order,
            'orders_count' => $orders->count(),
        ]);
        return view('manage.managementorder.index', compact('orders', 'sort', 'order','branchList', 'officeList'));
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
        // いずれの検索条件も満たしていない場合はfalseを返す
        Log::debug('【管理】注文検索条件なし', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'request' => $request->all(),
        ]);
        return false;
    }


    private function search(Request $request)
    {
        // 最初からリレーションを eager load
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
        if ($request->filled('branch')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('SHITEN_BU_CODE', $request->branch);
            });
        }
        if ($request->filled('office')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('EIGYOSHO_GROUP_CODE', $request->office);
            });
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

        // 🟢 追加：ビューで使う変数を定義
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        Log::debug('【管理】注文検索結果取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'sort' => $sort,
            'order' => $order,
            'orders_count' => $orders->count(),
            'search_conditions' => $request->all(),
        ]);

        return view('manage.managementorder.index', compact(
            'orders', 'sort', 'order', 'branchList', 'officeList'
        ));
    }




    public function show($id)
    {
        try {
            // 注文情報取得
            $order = DB::table('ORDER')->where('ORDER_CODE', $id)->first();

            // 注文詳細情報取得
            $tools = DB::table('ORDER_MEISAI')->where('ORDER_CODE', $id)->get();

            // 注文者の支店・部／営業所コード取得
            $user = DB::table('M_USER')
                ->where('USER_ID', $order->USER_ID)
                ->first();

            // 支店・部名取得
            $branchName = DB::table('M_SOSHIKI1')
                ->where('SHITEN_BU_CODE', $user->SHITEN_BU_CODE)
                ->value('SOSHIKI1_NAME');

            // 営業所・グループ名取得
            $officeName = DB::table('M_SOSHIKI2')
                ->where('EIGYOSHO_GROUP_CODE', $user->EIGYOSHO_GROUP_CODE)
                ->value('SOSHIKI2_NAME');

            // ログ出力
            Log::debug('【管理】注文詳細取得', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'ORDER_CODE' => $id,
                'order' => $order,
                'tools_count' => $tools->count(),
                'user' => $user,
                'branchName' => $branchName,
                'officeName' => $officeName,
            ]);

            return view('manage.managementorder.show', compact('order', 'tools', 'branchName', 'officeName', 'user'));

        } catch (\Exception $e) {
            // エラーログ出力
            Log::error('【管理】注文詳細取得エラー', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'ORDER_CODE' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 任意のエラー画面にリダイレクト、または適宜abortなど
            return redirect()->back()->with('error', '注文詳細の取得中にエラーが発生しました。');
        }
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

        // 注文詳細の更新
        $tools = $request->input('tools', []);
        foreach ($tools as $tool) {
            DB::table('ORDER_MEISAI')->where('ORDER_CODE', $id)->where('TOOL_CODE', $tool['TOOL_CODE'])->update([
                'TOOL_NAME' => $tool['TOOL_NAME'],
                'TOOL_COUNT' => $tool['TOOL_COUNT'],
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'Mops',
                'UPDATE_USER' => 'current_user'
            ]);
        }
        // ログ出力
        Log::debug('【管理】注文更新', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'ORDER_CODE' => $id,
            'ORDER_STATUS' => $request->input('ORDER_STATUS'),
            'ORDER_NAME' => $request->input('ORDER_NAME'),
            'ORDER_ADDRESS' => $request->input('ORDER_ADDRESS'),
            'ORDER_PHONE' => $request->input('ORDER_PHONE'),
            'tools_updated' => count($tools),
        ]);
        return redirect()->route('managementorder.index')->with('success', '注文情報を更新しました。');
    }
    
    // public function invoice($id)
    // {
    //     $order = Order::find($id);
    //     $user = User::find($order->USER_ID);
    //     $tools = OrderMeisai::where('ORDER_CODE', $order->ORDER_CODE)->get();
    //     $branchName = '〇〇支店'; 
    //     $officeName = '△△営業所';

    //     $pdf = Pdf::loadView('manage.managementorder.invoice', compact('order', 'user', 'tools', 'branchName', 'officeName'))
    //         ->setOption('defaultFont', 'ipaexg');

    //     return $pdf->download('納品書_'.$order->ORDER_CODE.'.pdf');
    // }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        $user = User::find($order->USER_ID);
        $tools = OrderMeisai::where('ORDER_CODE', $order->ORDER_CODE)->get();
        $branchName = '〇〇支店';
        $officeName = '△△営業所';

        return view('manage.managementorder.invoice', compact('order', 'user', 'tools', 'branchName', 'officeName'));
    }



    // 削除
    public function delete($id)
    {
        DB::table('ORDER')->where('ORDER_CODE', $id)->delete();
        DB::table('ORDER_MEISAI')->where('ORDER_CODE', $id)->delete();
        // ログ出力
        Log::debug('【管理】注文削除', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'ORDER_CODE' => $id,
        ]);

        return redirect()->route('managementorder.index')->with('success', '注文情報を削除しました。');
    }
}
