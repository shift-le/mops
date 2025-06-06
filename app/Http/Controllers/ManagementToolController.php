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
use Illuminate\Support\Facades\DB; // DBファサードをインポート
use Illuminate\Support\Facades\Auth; // 認証ファサードをインポート
use Illuminate\Support\Carbon; // Carbonライブラリをインポート
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
        if ($request->filled('RYOIKI')) {
            $query->where('RYOIKI', $request->input('RYOIKI'));
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

        // 検索結果取得（今回はとりあえず全件）
        $tools = $query->get();

        // マスタデータ
        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');
        $branches = User::select('SHITEN_BU_CODE')->distinct()->whereNotNull('SHITEN_BU_CODE')->pluck('SHITEN_BU_CODE');

        return view('manage.managementtool.index', compact('tools', 'ryoikis', 'hinmeis', 'branches'));
    }


    public function create() 
    { /* 新規 */
        return view('manage.managementtool.create'); 
    }


    public function store(Request $request) 
    { 
        /* 登録 */ 
        $request->validate([
            'tool_name' => 'required|string|max:255',
            'ryoiki_code' => 'required|exists:RYOIKI,RYOIKI_CODE',
            'hinmei_code' => 'required|exists:HINMEI,HINMEI_CODE',
            // 他のバリデーションルールを追加
        ]);
        $tool = new Tool();
        $tool->tool_name = $request->input('tool_name');
        $tool->ryoiki_code = $request->input('ryoiki_code');            
    }


    public function show($id)
    {
        $tool = Tool::find($id);
        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');  
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');
        // $toolKubun1 = ToolKubun::where('KUBUN_TYPE', '1')->pluck('KUBUN_NAME', 'KUBUN_CODE');
        // $toolKubun2 = ToolKubun::where('KUBUN_TYPE', '2')->pluck('KUBUN_NAME', 'KUBUN_CODE');
        
        if (!$tool) {
            abort(404);  // データが無ければ404
        }

        // return view('manage.managementtool.show', compact('tool', 'ryoikis', 'hinmeis', 'toolKubun1', 'toolKubun2'));
        return  view('manage.managementtool.show', compact('tool', 'ryoikis', 'hinmeis'));
    }


    public function delete($id) { /* 削除 */
        // ツールの削除ロジックを追加
        return redirect()->route('managementtool.index')->with('success', 'Tool deleted successfully.');
    }

    
    public function import() { /* インポート */ 
        return view('manage.managementtool.import');
    }


    public function importExec(Request $request)
    {
        // バリデーション
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('import_file');

        // スプレッドシート読み込み
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true); // A,B,C…形式

        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $currentUser = Auth::check() ? Auth::user()->USER_ID : 'system';

            // 2行目から処理
            foreach ($rows as $index => $row) {
                if ($index === 1) continue;
                if (empty($row['A'])) continue; // TOOL_CODE が空ならスキップ

                $toolCode = $row['A'];

                DB::table('TOOL')->updateOrInsert(
                    ['TOOL_CODE' => $toolCode],
                    [
                        'MST_FLG'               => $row['B'],
                        'DISPLAY_START_DATE'    => $this->parseDate($row['C']),
                        'DISPLAY_END_DATE'      => $this->parseDate($row['D']),
                        'KANRI_LIMIT_DATE'      => $this->parseDate($row['E']),
                        'SOSHIKI1'              => $row['F'] ?? '',
                        'SOSHIKI2'              => $row['G'] ?? '',
                        'TOOL_NAME_KANA'        => $row['H'],
                        'TOOL_NAME'             => $row['I'],
                        'TOOL_SHORT_NAME'       => $row['J'],
                        'IRISU'                 => $row['K'] ?? 0,
                        'SHUKKA_TANISU'         => $row['L'] ?? 0,
                        'MAX_ORDER'             => $row['M'],
                        'RYOIKI'                => $row['N'],
                        'HINMEI'                => $row['O'],
                        'CATEGORY3'             => $row['P'],
                        'TOOL_TYPE1'            => $row['Q'],
                        'TOOL_TYPE2'            => $row['R'],
                        'KOTEI_KEYWORD3'        => $row['S'],
                        'UNIT_TYPE'             => $row['T'],
                        'TOOL_TYPE'             => $row['U'],
                        'ZAIKO_TYPE'            => $row['V'],
                        'SET_TYPE'              => $row['W'],
                        'YOSAN_KANRI_FLG'       => $row['X'],
                        'HATTHUTEN_KANRI_TYPE'  => $row['Y'],
                        'SHOUNIN_KAKUNIN_FLG'   => $row['Z'],
                        'HATTHU_KIJYUNCHI'      => $row['AA'],
                        'HATTHU_TANI'           => $row['AB'],
                        'JAN_CODE'              => $row['AC'],
                        'KATABAN'               => $row['AD'],
                        'SHIIRESAKI_CODE'       => $row['AE'],
                        'TANKA'                 => $row['AF'],
                        'TOOL_KANRI_FLG'        => $row['AG'] ?? 1,
                        'TOOL_SETSUMEI'         => $row['AH'],
                        'REMARKS'               => $row['AI'],
                        'YOSAN_KANRIKANOU_USER_DOUITSU_FLG' => $row['AJ'],
                        'NEW_FLG'               => $row['AK'],
                        'NEW_DISPLAY_START_DATE'=> $this->parseDate($row['AL']),
                        'NEW_DISPLAY_END_DATE'  => $this->parseDate($row['AM']),
                        'SERIAL_NUM_KANRI_FLG'  => $row['AN'],
                        'LOTNO_KANRI_FLG'       => $row['AO'],
                        'YUKOUKIGEN_KANRI_FLG'  => $row['AP'],
                        'JYOTAI_KANRI_FLG'      => $row['AQ'],
                        'FUKUROZUME_KONPOUZAI_FLG' => $row['AR'],
                        'TOOL_ORDER_KANRI_FLG'  => $row['AS'],
                        'DISPLAY_START_DATE1'   => $this->parseDate($row['AT']),
                        'DISPLAY_END_DATE2'     => $this->parseDate($row['AU']),
                        'TOOL_NAME3'            => $row['AV'],
                        'TOOL_SETSUMEI4'        => $row['AW'],
                        'ORDER_KANOUSU_FROM'    => $row['AX'],
                        'ORDER_MAX'             => $row['AY'],
                        'DISPLAY_MAX'           => $row['AZ'],
                        // … 必要なら続きもここに追加

                        'DEL_FLG'               => 0,
                        'UPDATE_DT'             => $now,
                        'UPDATE_APP'            => 'ExcelImport',
                        'UPDATE_USER'           => $currentUser,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('managementtool.import')->with('success', 'ツール情報のインポートが完了しました。');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('managementtool.import')->with('error', 'インポートエラー：' . $e->getMessage());
        }
    }

    /**
     * 日付セルのパース
     */
    private function parseDate($value)
    {
        return empty($value) ? null : Carbon::parse($value)->format('Y-m-d');
    }

}
