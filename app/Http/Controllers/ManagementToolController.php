<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool; // 将来のDB用モデル（今は使わない）
use App\Models\Ryoiki; // 領域モデルをインポート
use App\Models\Hinmei; // 品名モデルをインポート
use App\Models\Keijiban; // 掲示板モデルをインポート
use App\Models\Faq; // FAQモデルをインポート
use App\Models\User; // ユーザーモデルをインポート
use App\Models\ToolKubun; // ツール区分モデルをインポート
use App\Models\ToolType1; // ツールタイプ1モデルをインポート
use App\Models\ToolType2; // ツールタイプ2モデルをインポート
use Illuminate\Support\Facades\DB; // DBファサードをインポート
use Illuminate\Support\Facades\Auth; // 認証ファサードをインポート
use Illuminate\Support\Carbon; // Carbonライブラリをインポート
use Illuminate\Support\Facades\Log; // ログ出力用ファサードをインポート
use PhpOffice\PhpSpreadsheet\IOFactory; // スプレッドシート読み込み用ライブラリをインポート

class ManagementToolController extends Controller
{
    public function index(Request $request)
    {
        // 基本のクエリ
        $query = Tool::query();

        // キーワード検索（対象カラム選択式）
        if ($request->filled('TOOL') && is_array($request->input('search_target'))) {
            $keyword = $request->input('TOOL');
            $targets = $request->input('search_target');
            $query->where(function ($q) use ($keyword, $targets) {
                foreach ($targets as $target) {
                    $q->orWhere($target, 'like', "%{$keyword}%");
                }
            });
        }

        // 領域検索
        if ($request->filled('M_RYOIKI')) {
            $query->where('M_RYOIKI', $request->input('RYOIKI'));
        }

        // 品名検索
        if ($request->filled('HINMEI')) {
            $query->where('HINMEI', $request->input('HINMEI'));
        }

        // ステータス検索
        if (!is_null($request->input('TOOL_STATUS'))) {
            $query->where('TOOL_STATUS', $request->input('TOOL_STATUS'));
        }

        // 表示期間検索
        if ($request->filled('display_start_from') && $request->filled('display_end_to')) {
            $from = $request->input('display_start_from');
            $to = $request->input('display_end_to');
            $query->where(function($q) use ($from, $to) {
                $q->whereDate('DISPLAY_START_DATE1', '>=', $from)
                ->whereDate('DISPLAY_END_DATE2', '<=', $to);
            });
        }

        // Mops登録日検索（CREATE_DT）
        if ($request->filled('create_dt_from') && $request->filled('create_dt_to')) {
            $from = $request->input('create_dt_from');
            $to = $request->input('create_dt_to');
            $query->whereBetween('CREATE_DT', [$from, $to]);
        }

        // 件数（デフォルトは10件）
        $perPage = $request->input('per_page', 10);

        // 検索結果取得（ページネーション）
        $tools = $query->paginate($perPage)->withQueryString();

        // マスタデータ
        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');
        $branches = User::select('SHITEN_BU_CODE')->distinct()->whereNotNull('SHITEN_BU_CODE')->pluck('SHITEN_BU_CODE');

        // ログ出力
        Log::debug('【管理】ツール一覧取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'search_conditions' => $request->all(),
            'tools_count' => $tools->total(),
        ]);

        // viewへ渡す
        return view('manage.managementtool.index', compact('tools', 'ryoikis', 'hinmeis', 'branches', 'perPage'));
    }



    public function create()
    {
        // マスタデータ
        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');

        // 追加する箇所
        $toolType1s = ToolType1::pluck('TOOL_TYPE1_NAME', 'TOOL_TYPE1');
        $toolType2s = ToolType2::pluck('TOOL_TYPE2_NAME', 'TOOL_TYPE2');

        // ログ出力
        Log::debug('【管理】ツール新規作成画面表示', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'ryoikis_count' => $ryoikis->count(),
            'hinmeis_count' => $hinmeis->count(),
            'toolType1s_count' => $toolType1s->count(),
            'toolType2s_count' => $toolType2s->count(),
        ]);
        return view('manage.managementtool.create', compact('ryoikis', 'hinmeis', 'toolType1s', 'toolType2s'));
    }


    public function store(Request $request)
    {
        $now = now();
        // $currentUser = Auth::user()->USER_ID;

        // バリデーションもここでやるのが理想（省略）

        // ファイルアップロード処理（PDF）
        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'public');
        }

        // ファイルアップロード処理（サムネ画像）
        $thumbPath = null;
        if ($request->hasFile('thumbnail_image')) {
            $thumbPath = $request->file('thumbnail_image')->store('thumbnails', 'public');
        }

        // データ登録
        DB::table('TOOL')->insert([
            // 仮のコード３行
            'CREATE_USER'    => 'system', // 仮の作成ユーザー、実際は認証ユーザーから取得
            'UPDATE_USER'    => 'system', // 仮の更新ユーザー、実際は認証ユーザーから取得
            'TOOL_CODE'         => $request->TOOL_CODE,
            'TOOL_NAME'         => $request->TOOL_NAME,
            'TOOL_NAME_KANA'    => $request->TOOL_NAME_KANA,
            'TOOL_STATUS'       => $request->TOOL_STATUS,
            'RYOIKI'            => $request->RYOIKI,
            'HINMEI'            => $request->HINMEI,
            'TOOL_TYPE1'        => $request->TOOL_TYPE1,
            'TOOL_TYPE2'        => $request->TOOL_TYPE2,
            'TOOL_SETSUMEI'     => $request->TOOL_SETUMEI,
            'REMARKS'           => $request->REMARKS,
            'DISPLAY_START_DATE'=> $request->HYOJI_START_DATE,
            'DISPLAY_END_DATE'  => $request->HYOJI_END_DATE,
            'TANKA'             => $request->TANKA,
            'TOOL_PDF_FILE'     => $pdfPath,
            'TOOL_THUM_FILE'    => $thumbPath,
            'ADMIN_MEMO'        => $request->ADMIN_MEMO,
            'CREATE_DT'         => $now,
            'CREATE_APP'        => 'WebForm',
            // 'CREATE_USER'       => $currentUser,
            'UPDATE_DT'         => $now,
            'UPDATE_APP'        => 'WebForm',
            // 'UPDATE_USER'       => $currentUser,
        ]);

        // ログ出力
        Log::debug('【管理】ツール新規登録', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'TOOL_CODE' => $request->TOOL_CODE,
            'TOOL_NAME' => $request->TOOL_NAME,
            'TOOL_STATUS' => $request->TOOL_STATUS,
            'RYOIKI' => $request->RYOIKI,
            'HINMEI' => $request->HINMEI,
            'TOOL_TYPE1' => $request->TOOL_TYPE1,
            'TOOL_TYPE2' => $request->TOOL_TYPE2,
        ]);

        return redirect()->route('managementtool.index')->with('success', 'ツール情報を登録しました。');
    }




    public function show($id)
    {
        $tool = Tool::find($id);
        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');  
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');
        $toolType1s = ToolType1::pluck('TOOL_TYPE1_NAME', 'TOOL_TYPE1');
        $toolType2s = ToolType2::pluck('TOOL_TYPE2_NAME', 'TOOL_TYPE2');
        
        if (!$tool) {
            abort(404);  // データが無ければ404
        }

        // ログ出力
        Log::debug('【管理】ツール詳細表示', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'TOOL_CODE' => $tool->TOOL_CODE,
            'TOOL_NAME' => $tool->TOOL_NAME,
            'TOOL_STATUS' => $tool->TOOL_STATUS,
            'RYOIKI' => $tool->RYOIKI,
            'HINMEI' => $tool->HINMEI,
            'TOOL_TYPE1' => $tool->TOOL_TYPE1,
            'TOOL_TYPE2' => $tool->TOOL_TYPE2,
        ]);
        // return view('manage.managementtool.show', compact('tool', 'ryoikis', 'hinmeis', 'toolKubun1', 'toolKubun2'));
        return  view('manage.managementtool.show', compact('tool', 'ryoikis', 'hinmeis', 'toolType1s', 'toolType2s'));
    }


    public function update(Request $request, $id)
    {
        // バリデーション
        $validatedData = $request->validate([
            'TOOL_STATUS' => 'required|integer',
            'TOOL_NAME' => 'required|string|max:255',
            'TOOL_NAME_KANA' => 'required|string|max:255',
            'TOOL_CODE' => 'required|string|max:50',
            'RYOIKI' => 'nullable|string|max:50',
            'HINMEI' => 'nullable|string|max:50',
            'TOOL_TYPE1' => 'nullable|string|max:50',
            'TOOL_TYPE2' => 'nullable|string|max:50',
            'TOOL_SETUMEI' => 'nullable|string|max:255',
            'REMARKS' => 'nullable|string|max:255',
            'TANKA' => 'required|integer|min:0',
            'DISPLAY_START_DATE' => 'required|date',
            'DISPLAY_END_DATE' => 'required|date',
            'TOOL_SETSUMEI4' => 'nullable|string',
            'MST_FLG' => 'nullable|integer',
            'KANRI_LIMIT_DATE' => 'nullable|date',
            'SOSHIKI1' => 'nullable|string|max:100',
            'SOSHIKI2' => 'nullable|string|max:100',
            'TOOL_MANAGER10_ID' => 'nullable|string|max:50',
            'TOOL_MANAGER10_NAME' => 'nullable|string|max:100',
            'TOOL_PDF_FILE' => 'nullable|file|mimes:pdf',
            'TOOL_THUM_FILE' => 'nullable|image|mimes:jpg,jpeg,png',
            'ADMIN_MEMO' => 'nullable|string|max:1000',
        ]);

        // ファイルアップロード処理
        $pdfPath = null;
        if ($request->hasFile('TOOL_PDF_FILE')) {
            $pdfPath = $request->file('TOOL_PDF_FILE')->store('pdfs', 'public');
        }

        $thumbPath = null;
        if ($request->hasFile('TOOL_THUM_FILE')) {
            $thumbPath = $request->file('TOOL_THUM_FILE')->store('thumbnails', 'public');
        }

        // 更新データセット
        $updateData = [
            'TOOL_STATUS' => $validatedData['TOOL_STATUS'],
            'TOOL_NAME' => $validatedData['TOOL_NAME'],
            'TOOL_NAME_KANA' => $validatedData['TOOL_NAME_KANA'],
            'TOOL_CODE' => $validatedData['TOOL_CODE'],
            'RYOIKI' => $validatedData['RYOIKI'],
            'HINMEI' => $validatedData['HINMEI'],
            'TOOL_TYPE1' => $validatedData['TOOL_TYPE1'],
            'TOOL_TYPE2' => $validatedData['TOOL_TYPE2'],
            'TOOL_SETUMEI' => $validatedData['TOOL_SETUMEI'],
            'REMARKS' => $validatedData['REMARKS'],
            'TANKA' => $validatedData['TANKA'],
            'DISPLAY_START_DATE' => $validatedData['DISPLAY_START_DATE'],
            'DISPLAY_END_DATE' => $validatedData['DISPLAY_END_DATE'],
            'MANAGEMENT_MEMO' => $validatedData['TOOL_SETSUMEI4'],
            'MST_FLG' => $validatedData['MST_FLG'],
            'KANRI_LIMIT_DATE' => $validatedData['KANRI_LIMIT_DATE'],
            'SOSHIKI1' => $validatedData['SOSHIKI1'],
            'SOSHIKI2' => $validatedData['SOSHIKI2'],
            'TOOL_MANAGER10_ID' => $validatedData['TOOL_MANAGER10_ID'],
            'TOOL_MANAGER10_NAME' => $validatedData['TOOL_MANAGER10_NAME'],
            'ADMIN_MEMO' => $validatedData['ADMIN_MEMO'],
            'UPDATE_DT' => now(),
            'UPDATE_USER' => 'current_user', // 実際にはログインユーザー名に差し替え可
        ];

        // ファイルがあればパスもセット
        if ($pdfPath) {
            $updateData['TOOL_PDF_FILE'] = $pdfPath;
        }
        if ($thumbPath) {
            $updateData['TOOL_THUM_FILE'] = $thumbPath;
        }

        // 更新実行
        DB::table('TOOLS')->where('TOOL_CODE', $id)->update($updateData);

        // ログ出力
        Log::debug('【管理】ツール更新', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'TOOL_CODE' => $id,
            'TOOL_NAME' => $validatedData['TOOL_NAME'],
            'TOOL_STATUS' => $validatedData['TOOL_STATUS'],
            'RYOIKI' => $validatedData['RYOIKI'],
            'HINMEI' => $validatedData['HINMEI'],
            'TOOL_TYPE1' => $validatedData['TOOL_TYPE1'],
            'TOOL_TYPE2' => $validatedData['TOOL_TYPE2'],
        ]);
        // リダイレクト
        return redirect()->route('managementtool.index')->with('success', 'ツール情報を更新しました');
    }


    

    public function delete($id) { /* 削除 */
        // ツールの削除ロジックを追加
        //　ログ出力
        Log::debug('【管理】ツール削除', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'TOOL_CODE' => $id,
        ]);
        return redirect()->route('managementtool.index')->with('success', 'Tool deleted successfully.');
    }

    
    public function import() { /* インポート */ 

        // ログ出力
        Log::debug('【管理】ツールインポート画面表示');
        return view('manage.managementtool.import');
    }


    public function importExec(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();
        $errorMessages = [];

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('import_file'));
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $header = array_shift($rows); // 1行目のカラム名
            $cloudFolderPath = storage_path('app/public/tool_files/');

            foreach ($rows as $index => $row) {
                $data = array_combine(array_values($header), array_values($row));
                if (empty($data['ツールコード'])) {
                    $errorMessages[] = "行 ".($index+2)."：ツールコードが未入力です。";
                    continue;
                }

                $tool = Tool::where('TOOL_CODE', $data['ツールコード'])->first();

                if ($tool) {
                    // 更新：既存が空＆インポート値がある場合のみ更新
                    foreach ($data as $column => $value) {
                        $dbColumn = $this->convertColumnName($column);
                        if (isset($tool->$dbColumn) && empty($tool->$dbColumn) && !empty($value)) {
                            $tool->$dbColumn = $value;
                        }
                    }
                } else {
                    // 新規登録
                    $tool = new Tool();
                    foreach ($data as $column => $value) {
                        $dbColumn = $this->convertColumnName($column);
                        $tool->$dbColumn = $value;
                    }
                    // TOOL_STATUSを強制設定
                    $tool->TOOL_STATUS = '1';
                }

                // サムネイル・PDFファイルパスの設定
                $toolName = $data['ツール名'];
                $thumPath = $cloudFolderPath . $toolName . '.jpg';
                $pdfPath = $cloudFolderPath . $toolName . '.pdf';

                $tool->TOOL_THUM_FILE = file_exists($thumPath) ? $thumPath : null;
                $tool->TOOL_PDF_FILE = file_exists($pdfPath) ? $pdfPath : null;

                if (!$tool->save()) {
                    $errorMessages[] = "行 ".($index+2)."：保存エラー";
                }
            }

            if (!empty($errorMessages)) {
                DB::rollBack();
                return redirect()->route('managementtool.import')
                    ->with('errors', implode("\n", $errorMessages));  // ←ここ
            }

            // ログ出力
            Log::debug('【管理】ツールインポート完了', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'imported_rows' => count($rows),
                'errors' => $errorMessages,
            ]);

            DB::commit();
            return redirect()->route('managementtool.import')
                ->with('success', 'インポート完了しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['import_error' => 'インポート中にエラー発生：'.$e->getMessage()]);
        }
    }

        private function convertColumnName($excelColumnName)
    {
        $map = [
            'ツールコード'                => 'TOOL_CODE',
            'MSTフラグ'                  => 'MST_FLG',
            'ツールステータス'             => 'TOOL_STATUS',
            '表示開始日'                  => 'DISPLAY_START_DATE',
            '表示終了日'                  => 'DISPLAY_END_DATE',
            '管理期限'                    => 'KANRI_LIMIT_DATE',
            '第1組織'                    => 'SOSHIKI1',
            '第2組織'                    => 'SOSHIKI2',
            'ツール名カナ'               => 'TOOL_NAME_KANA',
            'ツール名'                   => 'TOOL_NAME',
            'ツール略称'                 => 'TOOL_SHORT_NAME',
            '入数'                       => 'IRISU',
            '出荷単位数'                 => 'SHUKKA_TANISU',
            '最大注文数'                 => 'MAX_ORDER',
            '領域'                       => 'RYOIKI',
            '品名'                       => 'HINMEI',
            'カテゴリ3'                  => 'CATEGORY3',
            'ツール区分１'               => 'TOOL_TYPE1',
            'ツール区分２'               => 'TOOL_TYPE2',
            '固定キーワード3'            => 'KOTEI_KEYWORD3',
            '単位区分'                   => 'UNIT_TYPE',
            '商品区分'                   => 'TOOL_TYPE',
            '在庫区分'                   => 'ZAIKO_TYPE',
            'セット区分'                 => 'SET_TYPE',
            '予算管理フラグ'             => 'YOSAN_KANRI_FLG',
            '発注点管理区分'             => 'HATTHUTEN_KANRI_TYPE',
            '承認確認フラグ'             => 'SHOUNIN_KAKUNIN_FLG',
            '発注基準値'                 => 'HATTHU_KIJYUNCHI',
            '発注単位'                   => 'HATTHU_TANI',
            'JANコード'                  => 'JAN_CODE',
            '型番'                       => 'KATABAN',
            '仕入先コード'               => 'SHIIRESAKI_CODE',
            '単価'                       => 'TANKA',
            'ツール管理フラグ'           => 'TOOL_KANRI_FLG',
            'ツール説明'                 => 'TOOL_SETSUMEI',
            '備考'                       => 'REMARKS',
            '予算管理可能ユーザー同一フラグ' => 'YOSAN_KANRIKANOU_USER_DOUITSU_FLG',
            '新着フラグ'                 => 'NEW_FLG',
            '新着表示開始日'             => 'NEW_DISPLAY_START_DATE',
            '新着表示終了日'             => 'NEW_DISPLAY_END_DATE',
            'シリアルナンバー管理フラグ'   => 'SERIAL_NUM_KANRI_FLG',
            'LotNo管理フラグ'            => 'LOTNO_KANRI_FLG',
            '有効期限管理フラグ'         => 'YUKOUKIGEN_KANRI_FLG',
            '状態管理フラグ'             => 'JYOTAI_KANRI_FLG',
            '袋詰め梱包材フラグ'         => 'FUKUROZUME_KONPOUZAI_FLG',
            'ツールオーダー管理フラグ'   => 'TOOL_ORDER_KANRI_FLG',
            '表示開始日'                  => 'DISPLAY_START_DATE1',
            '表示終了日'                  => 'DISPLAY_END_DATE2',
            'ツール名'                   => 'TOOL_NAME3',
            'ツール説明'                 => 'TOOL_SETSUMEI4',
            '注文可能数FROM'             => 'ORDER_KANOUSU_FROM',
            '注文上限数'                 => 'ORDER_MAX',
            '表示限度数'                 => 'DISPLAY_MAX',
            '領域CD2'                    => 'RYOIKI_CD2',
            '品名カテゴリCD2'            => 'HINMEI_CATEGORY_CD2',
            '領域CD3'                    => 'RYOIKI_CD3',
            '品名カテゴリCD3'            => 'HINMEI_CATEGORY_CD3',
            '領域CD4'                    => 'RYOIKI_CD4',
            '品名カテゴリCD4'            => 'HINMEI_CATEGORY_CD4',
            '領域CD5'                    => 'RYOIKI_CD5',
            '品名カテゴリCD5'            => 'HINMEI_CATEGORY_CD5',
            'ツール管理者1ID'            => 'TOOL_MANAGER1_ID',
            'ツール管理者1氏名'          => 'TOOL_MANAGER1_NAME',
            'ツール管理者2ID'            => 'TOOL_MANAGER2_ID',
            'ツール管理者2氏名'          => 'TOOL_MANAGER2_NAME',
            'ツール管理者3ID'            => 'TOOL_MANAGER3_ID',
            'ツール管理者3氏名'          => 'TOOL_MANAGER3_NAME',
            'ツール管理者4ID'            => 'TOOL_MANAGER4_ID',
            'ツール管理者4氏名'          => 'TOOL_MANAGER4_NAME',
            'ツール管理者5ID'            => 'TOOL_MANAGER5_ID',
            'ツール管理者5氏名'          => 'TOOL_MANAGER5_NAME',
            'ツール管理者6ID'            => 'TOOL_MANAGER6_ID',
            'ツール管理者6氏名'          => 'TOOL_MANAGER6_NAME',
            'ツール管理者7ID'            => 'TOOL_MANAGER7_ID',
            'ツール管理者7氏名'          => 'TOOL_MANAGER7_NAME',
            'ツール管理者8ID'            => 'TOOL_MANAGER8_ID',
            'ツール管理者8氏名'          => 'TOOL_MANAGER8_NAME',
            'ツール管理者9ID'            => 'TOOL_MANAGER9_ID',
            'ツール管理者9氏名'          => 'TOOL_MANAGER9_NAME',
            'ツール管理者10ID'           => 'TOOL_MANAGER10_ID',
            'ツール管理者10氏名'         => 'TOOL_MANAGER10_NAME',
            '管理者メモ'                 => 'ADMIN_MEMO',
            // …以降同様に10人分の管理者IDと氏名もここに記述
        ];

        return $map[$excelColumnName] ?? null;
    }



    private function setToolValues($tool, $row)
    {
        $columns = [
            'TOOL_THUM_FILE', 'TOOL_PDF_FILE', 'TOOL_NAME', /* 他カラム */
        ];

        foreach ($columns as $index => $col) {
            $tool->$col = $row[$index];
        }

        // ファイル名セット
        $this->setToolFiles($tool);
    }


    private function updateToolIfEmpty($tool, $row)
    {
        $columns = [
            'TOOL_THUM_FILE', 'TOOL_PDF_FILE', 'TOOL_NAME', /* 他カラム */
        ];

        $updated = false;

        foreach ($columns as $index => $col) {
            if (empty($tool->$col) && !empty($row[$index])) {
                $tool->$col = $row[$index];
                $updated = true;
            }
        }

        if ($updated) {
            $this->setToolFiles($tool);
            $tool->save();
        }
    }


    private function setToolFiles($tool)
    {
        $basePath = storage_path('app/public/tools/');
        $fileName = $tool->TOOL_NAME;

        // サムネ画像
        $thumbPath = $basePath . $fileName . '.jpg';
        if (file_exists($thumbPath)) {
            $tool->TOOL_THUM_FILE = 'tools/' . $fileName . '.jpg';
        }

        // PDFファイル
        $pdfPath = $basePath . $fileName . '.pdf';
        if (file_exists($pdfPath)) {
            $tool->TOOL_PDF_FILE = 'tools/' . $fileName . '.pdf';
        }
    }



        public function NoticeStatus(Request $request)
    {
        $validated = $request->validate([
            'selected_tools' => 'required|array',
            'status' => 'required|in:0,1,2,3,4'
        ]);

        $toolCodes = $validated['selected_tools'];
        $newStatus = $validated['status'];

        // ステータス更新
        Tool::whereIn('TOOL_CODE', $toolCodes)->update(['TOOL_STATUS' => $newStatus]);

        // メール送信
        Mail::to('nakajima@example.com')->send(new StatusUpdateNotification($toolCodes, $newStatus));

        return redirect()->route('managementtool.index')->with('success', 'ステータス変更と通知を実行しました');
    }

    /**
     * 日付セルのパース
     */
    private function parseDate($value)
    {
        return empty($value) ? null : Carbon::parse($value)->format('Y-m-d');
    }

}
