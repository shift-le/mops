<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // ログ出力用
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Carbon;
use App\Models\User; // 仮データ用モデル（今は使わない）
use App\Models\Thuzaiin;

// use App\Models\ManagementUser; // 将来のDB用モデル（今は使わない）

class ManagementUserController extends Controller
{
    public function index(Request $request)
    {
        if ($this->hasSearchConditions($request)) {
            return $this->search($request);
        }

        // デフォルト一覧表示
        $query = DB::table('M_USER')
            ->select('USER_ID', 'NAME', 'NAME_KANA', 'EMAIL', 'SHITEN_BU_CODE', 'CREATE_DT')
            ->where('DEL_FLG', 0)
            ->orderBy('USER_ID', 'asc');

        $users = $query->paginate(15)->appends($request->all());

        // 駐在員ID一覧取得
        $residentIds = $this->getResidentIds();

        // 支店・部名一覧取得（コードと名称のペア）
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループ名一覧取得（コードと名称のペア）
        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        //　ログ出力
        Log::debug('【管理】ユーザー一覧取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'users_count' => $users->count(),
            'residentIds_count' => count($residentIds),
            'branchList_count' => count($branchList),
            'officeList_count' => count($officeList),
        ]);
        return view('manage.managementuser.index', compact('users', 'residentIds', 'branchList', 'officeList'));
    }

    private function hasSearchConditions(Request $request)
    {
        // ログ出力
        Log::debug('【管理】ユーザー検索条件チェック', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'request' => $request->all(),
        ]);
        return $request->filled('user') || $request->filled('search_target') || $request->filled('branch') || $request->filled('office') || $request->filled('resident');
    }

    private function search(Request $request)
    {
        $query = DB::table('M_USER')
            ->select('USER_ID', 'NAME', 'NAME_KANA', 'EMAIL', 'SHITEN_BU_CODE', 'CREATE_DT');

        // キーワード検索
        if ($request->filled('user') && $request->filled('search_target')) {
            $keyword = trim($request->input('user'));
            $targets = $request->input('search_target');

            $query->where(function ($q) use ($keyword, $targets) {
                foreach ($targets as $target) {
                    $q->orWhere($target, 'like', "%$keyword%");
                }
            });
        }

        // 支店・部
        if ($request->filled('branch')) {
            $query->where('SHITEN_BU_CODE', $request->input('branch'));
        }

        // 営業所グループ
        if ($request->filled('office')) {
            $query->where('EIGYOSHO_GROUP_CODE', $request->input('office'));
        }

        // 駐在員検索
        if ($request->filled('resident')) {
            $residentIds = $this->getResidentIds();
            $query->whereIn('USER_ID', $residentIds);
        }

        $sort = $request->input('sort', 'USER_ID');
        $order = $request->input('order', 'asc');

        $query->orderBy($sort, $order);

        $users = $query->paginate(15)->appends($request->all());

        // 駐在員ID一覧取得
        $residentIds = $this->getResidentIds();

        // 支店・部名一覧取得（コードと名称のペア）
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループ名一覧取得（コードと名称のペア）
        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        // ログ出力
        Log::debug('ManagementUserController@search accessed.', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'query' => $request->query(), // GETパラメータ
            'users_count' => $users->count(),
            'residentIds_count' => count($residentIds),
            'branchList_count' => count($branchList),
            'officeList_count' => count($officeList),
        ]);
        return view('manage.managementuser.index', compact('users', 'residentIds', 'branchList', 'officeList'));
    }

    private function getResidentIds()
    {
        // 駐在員ID一覧を取得
        return DB::table('M_THUZAIIN')->pluck('USER_ID')->toArray();
    }

    

    public function show($id)
    {
        // 指定IDのユーザー取得
        $user = collect($users)->firstWhere('USER_ID', (int)$id);

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('manage.managementuser.show', compact('user'));
    }


    public function create()
    {
        // 支店・部の一覧取得（コードと名称）
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループの一覧取得（コードと名称）
        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        // 都道府県一覧取得：M_GENERAL_TYPE の TYPE_CODE='PREFECTURE' のレコードのみ
        $prefectures = DB::table('M_GENERAL_TYPE')
            ->where('TYPE_CODE', 'PREFECTURE')
            ->pluck('VALUE', 'KEY'); // KEY＝ID、VALUE＝都道府県名

        Log::debug('【管理】ユーザー新規作成', [
            'method_name' => __METHOD__,
        ]);
        // ビューに渡す
        return view('manage.managementuser.create', compact('branchList', 'officeList', 'prefectures'));
    }



    public function store(Request $request)
    {
        Log::DEBUG('【カンリ】新規作成】');
        // バリデーション
        $request->validate([
            'USER_ID'              => 'required|string|unique:M_USER,USER_ID',
            'SHAIN_ID'             => 'required|string',
            'NAME'                 => 'required|string',
            'NAME_KANA'            => 'required|string',
            'EMAIL'                => 'nullable|email',
            'MOBILE_TEL'           => 'nullable|required|string',
            'MOBILE_EMAIL'         => 'nullable|email',
            'SHITEN_BU_CODE'       => 'nullable|required',
            'EIGYOSHO_GROUP_CODE'  => 'nullable|required',
            // 駐在員にチェックがあればフィールド必須に
            'THUZAIIN_NAME'        => 'nullable|required_if:is_thuzaiin,1|string',
            'THUZAIIN_ZIP'         => 'nullable|required_if:is_thuzaiin,1|string',
            'THUZAIIN_PREF'        => 'nullable|required_if:is_thuzaiin,1|string',
            'THUZAIIN_ADDRESS1'    => 'nullable|required_if:is_thuzaiin,1|string',
            'THUZAIIN_TEL'         => 'nullable|required_if:is_thuzaiin,1|string',
        ]);
        Log::debug('バリデーション通過後'); // ← ここが出なければ validate() で止まってる

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $currentUser = Auth::user()?->USER_ID ?? 'system';

            // ユーザー登録
            $userinsert=DB::table('M_USER')->insert([
                'USER_ID'             => $request->USER_ID,
                'SHAIN_ID'            => $request->SHAIN_ID,
                'NAME'                => $request->NAME,
                'NAME_KANA'           => $request->NAME_KANA,
                'PASSWORD'            => Hash::make($request->SHAIN_ID), // パスは SHAIN_ID を初期パスにするなら hash 化必須
                'EMAIL'               => $request->EMAIL,
                'MOBILE_TEL'          => $request->MOBILE_TEL,
                'MOBILE_EMAIL'        => $request->MOBILE_EMAIL,
                'SHITEN_BU_CODE'      => $request->SHITEN_BU_CODE,
                'EIGYOSHO_GROUP_CODE' => $request->EIGYOSHO_GROUP_CODE,
                'ROLE_ID'             => 'MU01',
                'UPDATE_FLG'          => 1,
                'DEL_FLG'             => 0,
                'CREATE_DT'           => $now,
                'CREATE_APP'          => 'Mops',
                'CREATE_USER'         => $currentUser,
                'UPDATE_DT'           => $now,
                'UPDATE_APP'          => 'Mops',
                'UPDATE_USER'         => $currentUser,
            ]);

            // 駐在員チェックがあれば登録
            if ($request->input('is_thuzaiin') === '1') {
                Thuzaiin::create([
                    'USER_ID'     => $request->USER_ID,
                    'POST_CODE'   => $request->THUZAIIN_ZIP,
                    'PREFECTURE'  => $request->THUZAIIN_PREF,
                    // 'PREF_ID'  => $request->THUZAIIN_PREF,
                    'ADDRESS1'    => $request->THUZAIIN_ADDRESS1,
                    'ADDRESS2'    => $request->THUZAIIN_ADDRESS2,
                    'ADDRESS3'    => $request->THUZAIIN_ADDRESS3,
                    'TEL'         => $request->THUZAIIN_TEL,
                    'DEL_FLG'     => 0,
                    'CREATE_DT'   => $now,
                    'CREATE_APP'  => 'Mops',
                    'CREATE_USER' => $currentUser,
                    'UPDATE_DT'   => $now,
                    'UPDATE_APP'  => 'Mops',
                    'UPDATE_USER' => $currentUser,
                ]);
            }

            Log::debug('新規登録結果',['INSERT_RESULT'=>$userinsert]);
            DB::commit();
            return redirect()->route('managementuser.index')
                ->with('message', 'ユーザーを登録しました');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('登録失敗: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return back()->with('error', '登録に失敗しました：' . $e->getMessage());
        }
    }



    public function importExec(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('import_file');
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath())->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $header = array_shift($rows);

        $errors = [];
        $now = \Carbon\Carbon::now();
        $insert = [];
        $update = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                $rowNum = $i + 2; // Excel 上の行番号
                $rowErr = [];

                // 必須検証
                if (empty($row['A'])) {
                    $rowErr[] = 'USER_ID が未入力です';
                }
                if (!empty($row['G']) && !filter_var($row['G'], FILTER_VALIDATE_EMAIL)) {
                    $rowErr[] = 'メール形式が不正です';
                }

                if ($rowErr) {
                    $errors[] = ['row' => $rowNum, 'messages' => $rowErr];
                    continue;
                }

                $userId = $row['A'];
                $exists = DB::table('M_USER')->where('USER_ID', $userId)->exists();

                // 初期パスワードは SHAIN_ID（列 C） or デフォルト
                $initialPass = !empty($row['C']) ? $row['C'] : 'password';

                // ベースデータ
                $data = [
                    'USER_ID'             => $userId,
                    'PASSWORD'            => Hash::make($initialPass),
                    'EMAIL'               => $row['G'] ?? '',
                    'MOBILE_TEL'          => $row['H'] ?? '',
                    'SHAIN_ID'            => $row['C'] ?? '',
                    'NAME'                => $row['D'] ?? '',
                    'NAME_KANA'           => $row['E'] ?? '',
                    'MOBILE_EMAIL'        => $row['I'] ?? '',
                    'SHITEN_BU_CODE'      => $row['K'] ?? '',
                    'EIGYOSHO_GROUP_CODE' => $row['L'] ?? '',
                    'ROLE_ID'             => 'MU01',
                    'DEL_FLG'             => 0,
                    'UPDATE_FLG'          => '1',
                    'CREATE_APP'          => 'MopsImport',
                    'CREATE_DT'           => $now,
                    'CREATE_USER'         => auth()->user()->USER_ID ?? 'system',
                    'UPDATE_APP'          => 'MopsImport',
                    'UPDATE_DT'           => $now,
                    'UPDATE_USER'         => auth()->user()->USER_ID ?? 'system',
                ];

                if ($exists) {
                    // UPDATE 処理：既存なら PASSWORD を除外して更新
                    unset($data['PASSWORD']);
                    $update[] = ['USER_ID' => $userId, 'data' => $data];
                } else {
                    // INSERT 処理
                    $insert[] = $data;
                }
            }

            if ($errors) {
                DB::rollBack();
                return redirect()->route('managementuser.import')
                    ->with('import_errors', $errors);
            }

            // DB反映
            foreach ($update as $u) {
                DB::table('M_USER')->where('USER_ID', $u['USER_ID'])->update($u['data']);
            }
            if ($insert) {
                DB::table('M_USER')->insert($insert);
            }

            Log::debug('User import executed', [
                'inserts' => count($insert),
                'updates' => count($update),
            ]);
            DB::commit();

            return redirect()->route('managementuser.import')
                ->with('success', 'インポートが完了しました。（新規:' . count($insert) . '件、更新:' . count($update) . '件）');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('importExec exception', ['e' => $e->getMessage()]);
            return redirect()->route('managementuser.import')
                ->with('error', 'インポート中にエラーが発生しました：' . $e->getMessage());
        }
    }




    public function import()
    {
        // インポート画面表示
        return view('manage.managementuser.import');
    }


    public function exportExec()
    {
        // データ取得
        $users = DB::table('M_USER')->get();

        // スプレッドシート作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ヘッダ行
        $headers = [
            'ユーザーID', '社員ID', '氏名漢字', '氏名カナ', 'パスワード', 'メールアドレス',
            '携帯電話番号', '携帯メールアドレス', '支店・部コード', '営業所・グループコード',
            '権限ID', '削除フラグ', '更新フラグ', '作成日', '作成アプリ', '作成ユーザー',
            '更新日', '更新アプリ', '更新ユーザー'
        ];

        // ヘッダのセット
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '1', $header);
            $columnIndex++;
        }

        // データのセット
        $rowIndex = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $rowIndex, $user->USER_ID);
            $sheet->setCellValue('B' . $rowIndex, $user->SHAIN_ID);
            $sheet->setCellValue('C' . $rowIndex, $user->NAME);
            $sheet->setCellValue('D' . $rowIndex, $user->NAME_KANA);
            $sheet->setCellValue('E' . $rowIndex, $user->PASSWORD);
            $sheet->setCellValue('F' . $rowIndex, $user->EMAIL);
            $sheet->setCellValue('G' . $rowIndex, $user->MOBILE_TEL);
            $sheet->setCellValue('H' . $rowIndex, $user->MOBILE_EMAIL);
            $sheet->setCellValue('I' . $rowIndex, $user->SHITEN_BU_CODE);
            $sheet->setCellValue('J' . $rowIndex, $user->EIGYOSHO_GROUP_CODE);
            $sheet->setCellValue('K' . $rowIndex, $user->ROLE_ID);
            $sheet->setCellValue('L' . $rowIndex, $user->DEL_FLG);
            $sheet->setCellValue('M' . $rowIndex, $user->UPDATE_FLG);
            $sheet->setCellValue('N' . $rowIndex, $user->CREATE_DT);
            $sheet->setCellValue('O' . $rowIndex, $user->CREATE_APP);
            $sheet->setCellValue('P' . $rowIndex, $user->CREATE_USER);
            $sheet->setCellValue('Q' . $rowIndex, $user->UPDATE_DT);
            $sheet->setCellValue('R' . $rowIndex, $user->UPDATE_APP);
            $sheet->setCellValue('S' . $rowIndex, $user->UPDATE_USER);

            $rowIndex++;
        }

        // ファイル名
        $fileName = 'users_export_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // レスポンスでExcelファイルダウンロード
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);


        // ログ出力
        Log::debug('User export executed successfully', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'file_name' => $fileName,
            'users_count' => $users->count()
        ]);
        // ダウンロードレスポンスを返す
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }


    public function detail($id)
    {
        $user = DB::table('M_USER')->where('USER_ID', $id)->first();

        if (!$user) {
            abort(404, 'ユーザーが見つかりません');
        }

        $thuzaiin = DB::table('M_THUZAIIN')->where('USER_ID', $id)->first();

        // 都道府県リスト取得
        $prefectures = DB::table('M_GENERAL_TYPE')
                        ->orderBy('KEY')
                        ->get();
        // 支店・部名一覧取得（コードと名称のペア）
        $branchList = DB::table('M_SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループ名一覧取得（コードと名称のペア）
        $officeList = DB::table('M_SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        //　ログ出力  
        Log::debug('ManagementUserController@detail accessed.', [
            'method_name' => __METHOD__,
            'http_method' => request()->method(),
            'USER_ID' => $id,
            'user' => $user,
            'thuzaiin' => $thuzaiin
        ]);
        return view('manage.managementuser.detail', compact('user', 'thuzaiin', 'prefectures', 'branchList', 'officeList'));
    }


    public function update(Request $request, $id)
    {
        try {
            // バリデーション
            $request->validate([
                'NAME' => 'required|string|max:255',
                'NAME_KANA' => 'required|string|max:255',
                'EMAIL' => 'required|email|max:255',
                'MOBILE_TEL' => 'nullable|string|max:20',
                'MOBILE_EMAIL' => 'nullable|string|max:255',
                'SHITEN_BU_CODE' => 'nullable|string|max:10',
                'EIGYOSHO_GROUP_CODE' => 'nullable|string|max:10',
                'is_thuzaiin' => 'nullable|boolean',
                'THUZAIIN_NAME' => 'nullable|string|max:255',
                'POST_CODE1' => 'nullable|digits:3',
                'POST_CODE2' => 'nullable|digits:4',
                'THUZAIIN_PREF' => 'nullable|integer',
                'ADDRESS1' => 'nullable|string|max:255',
                'ADDRESS2' => 'nullable|string|max:255',
                'ADDRESS3' => 'nullable|string|max:255',
                'TEL' => 'nullable|string|max:20',
            ]);

            // USERSテーブルの更新
            DB::table('M_USER')->where('USER_ID', $id)->update([
                'NAME' => $request->input('NAME'),
                'NAME_KANA' => $request->input('NAME_KANA'),
                'EMAIL' => $request->input('EMAIL'),
                'MOBILE_TEL' => $request->input('MOBILE_TEL'),
                'MOBILE_EMAIL' => $request->input('MOBILE_EMAIL'),
                'SHITEN_BU_CODE' => $request->input('SHITEN_BU_CODE'),
                'EIGYOSHO_GROUP_CODE' => $request->input('EIGYOSHO_GROUP_CODE'),
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'Mops',
                'UPDATE_USER' => '管理者',
            ]);

            if ($request->has('is_thuzaiin') && $request->input('is_thuzaiin')) {
                $postCode = $request->input('POST_CODE1') . $request->input('POST_CODE2');

                $thuzaiData = [
                    'THUZAIIN_NAME' => $request->input('THUZAIIN_NAME'),
                    'POST_CODE' => $postCode,
                    'PREFECTURE' => $request->input('THUZAIIN_PREF'),

                    'ADDRESS1' => $request->input('ADDRESS1'),
                    'ADDRESS2' => $request->input('ADDRESS2'),
                    'ADDRESS3' => $request->input('ADDRESS3'),
                    'TEL' => $request->input('TEL'),
                    'UPDATE_DT' => now(),
                    'UPDATE_APP' => 'Mops',
                    'UPDATE_USER' => '管理者',
                ];

                $exists = DB::table('M_THUZAIIN')->where('USER_ID', $id)->exists();
                if ($exists) {
                    DB::table('M_THUZAIIN')->where('USER_ID', $id)->update($thuzaiData);
                } else {
                    $thuzaiData['USER_ID'] = $id;
                    $thuzaiData['CREATE_DT'] = now();
                    $thuzaiData['CREATE_APP'] = 'Mops';
                    $thuzaiData['CREATE_USER'] = '管理者';
                    DB::table('M_THUZAIIN')->insert($thuzaiData);
                }
            }
            return redirect()->route('managementuser.index')->with('success', 'ユーザ情報を更新しました。');
        } catch (\Exception $e) {
            Log::error('【管理】ユーザ更新エラー', [
                'method' => __METHOD__,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return redirect()->back()->withInput()->with('error', 'ユーザ情報の更新中にエラーが発生しました。');
        }
    }


    public function delete($id)
    {
        try {
            // 論理削除：DEL_FLGを1に更新
            DB::table('M_USER')
                ->where('USER_ID', $id)
                ->update(['DEL_FLG' => 1]);

            // 関連データの物理削除
            DB::table('M_THUZAIIN')
                ->where('USER_ID', $id)
                ->update(['DEL_FLG' => 1]);

            // 成功ログ出力
            Log::debug('【管理】ユーザー削除（論理）', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'USER_ID'     => $id,
                'DELETED_BY'  => auth()->user()->USER_ID ?? 'system'
            ]);

            return redirect()->route('managementuser.index')
                ->with('success', 'ユーザーを論理削除しました。');

        } catch (\Exception $e) {
            // エラーログ出力
            Log::error('【管理】ユーザー削除エラー', [
                'method_name'    => __METHOD__,
                'http_method'    => request()->method(),
                'USER_ID'        => $id,
                'error_message'  => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
                'DELETED_BY'     => auth()->user()->USER_ID ?? 'system'
            ]);

            return redirect()->back()
                ->with('error', 'ユーザーの削除中にエラーが発生しました。');
        }
    }



    public function exportConfirm()
    {
        // ログ出力
        Log::debug('ManagementUserController@exportConfirm: Export confirmation page accessed.');
        return view('manage.managementuser.export');
    }

}
