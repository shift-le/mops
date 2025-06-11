<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
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
        $query = DB::table('USERS')
            ->select('USER_ID', 'NAME', 'NAME_KANA', 'EMAIL', 'SHITEN_BU_CODE', 'CREATE_DT')
            ->orderBy('USER_ID', 'asc');

        $users = $query->paginate(15)->appends($request->all());

        // 駐在員ID一覧取得
        $residentIds = $this->getResidentIds();

        // 支店・部名一覧取得（コードと名称のペア）
        $branchList = DB::table('SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループ名一覧取得（コードと名称のペア）
        $officeList = DB::table('SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        return view('manage.managementuser.index', compact('users', 'residentIds', 'branchList', 'officeList'));
    }

    private function hasSearchConditions(Request $request)
    {
        return $request->filled('user') || $request->filled('search_target') || $request->filled('branch') || $request->filled('office') || $request->filled('resident');
    }

    private function search(Request $request)
    {
        $query = DB::table('USERS')
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
        $branchList = DB::table('SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループ名一覧取得（コードと名称のペア）
        $officeList = DB::table('SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        return view('manage.managementuser.index', compact('users', 'residentIds', 'branchList', 'officeList'));
    }

    private function getResidentIds()
    {
        return DB::table('THUZAIIN')->pluck('USER_ID')->toArray();
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
        $branchList = DB::table('SOSHIKI1')
            ->pluck('SOSHIKI1_NAME', 'SHITEN_BU_CODE')
            ->toArray();

        // 営業所・グループの一覧取得（コードと名称）
        $officeList = DB::table('SOSHIKI2')
            ->pluck('SOSHIKI2_NAME', 'EIGYOSHO_GROUP_CODE')
            ->toArray();

        // ビューに渡す
        return view('manage.managementuser.create', compact('branchList', 'officeList'));
    }



    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $currentUser = Auth::user() ? Auth::user()->USER_ID : 'system';

            // ユーザー情報登録
            DB::table('USERS')->insert([
                'USER_ID'               => $request->USER_ID,
                'SHAIN_ID'              => $request->SHAIN_ID,
                'NAME'                  => $request->NAME,
                'NAME_KANA'             => $request->NAME_KANA,
                'PASSWORD'              => $request->SHAIN_ID,
                'EMAIL'                 => $request->EMAIL,
                'MOBILE_TEL'            => $request->MOBILE_TEL,
                'MOBILE_EMAIL'          => $request->MOBILE_EMAIL,
                'SHITEN_BU_CODE'        => $request->SHITEN_BU_CODE,
                'EIGYOSHO_GROUP_CODE'   => $request->EIGYOSHO_GROUP_CODE,
                'ROLE_ID'               => $request->ROLE_ID,
                'UPDATE_FLG'            => 1,
                'DEL_FLG'               => 0,
                'CREATE_DT'             => $now,
                'CREATE_APP'            => 'Mops',
                'CREATE_USER'           => $currentUser,
                'UPDATE_DT'             => $now,
                'UPDATE_APP'            => 'Mops',
                'UPDATE_USER'           => $currentUser,
            ]);

            // 駐在員情報が入力されていれば登録
            if ($request->has('is_thuzaiin')) {
                Thuzaiin::create([
                    'USER_ID'     => $request->USER_ID,
                    'POST_CODE'   => $request->THUZAIIN_ZIP,
                    'PREF_ID'     => $request->THUZAIIN_PREF,
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

            DB::commit();

            return redirect()->route('managementuser.index')->with('message', 'ユーザーを登録しました');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', '登録に失敗しました：' . $e->getMessage());
        }
    }


    public function importExec(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('import_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $errors = [];
        $now = Carbon::now();

        $insertData = [];
        $updateData = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                if ($index === 1) continue; // 1行目はヘッダー行

                $rowErrors = [];

                // USER_ID必須チェック
                if (empty($row['A'])) {
                    $rowErrors[] = "USER_IDが未入力";
                }

                // その他バリデーション例（メール形式チェック）
                if (!empty($row['G']) && !filter_var($row['G'], FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = "メールアドレスの形式が不正";
                }

                // 必須エラーチェックがあればこの行はスキップ
                if (!empty($rowErrors)) {
                    $errors[] = [
                        'row' => $index,
                        'messages' => $rowErrors
                    ];
                    continue;
                }

                $userId = $row['A'];
                $existingUser = DB::table('USERS')->where('USER_ID', $userId)->first();

                $data = [
                    'UPDATE_FLG'  => '1',
                    'UPDATE_DT'   => $now,
                    'UPDATE_APP'  => 'ExcelImport',
                    'UPDATE_USER' => 'import_user'
                ];

                // 空でないもののみセット
                if (!empty($row['C'])) $data['SHAIN_ID'] = $row['C'];
                if (!empty($row['D'])) $data['NAME'] = $row['D'];
                if (!empty($row['E'])) $data['NAME_KANA'] = $row['E'];
                if (!empty($row['G'])) $data['EMAIL'] = $row['G'];
                if (!empty($row['H'])) $data['MOBILE_TEL'] = $row['H'];
                if (!empty($row['I'])) $data['MOBILE_EMAIL'] = $row['I'];
                if (!empty($row['K'])) $data['SHITEN_BU_CODE'] = $row['K'];
                if (!empty($row['L'])) $data['EIGYOSHO_GROUP_CODE'] = $row['L'];
                if (!empty($row['N'])) $data['ROLE_ID'] = $row['N'];

                if ($existingUser) {
                    // UPDATE用データに格納
                    $updateData[] = [
                        'USER_ID' => $userId,
                        'data' => $data
                    ];
                } else {
                    // INSERT用データに格納
                    $data['USER_ID']  = $userId;
                    $data['PASSWORD'] = !empty($row['C']) ? Hash::make($row['C']) : Hash::make('default_password');
                    $data['DEL_FLG']  = 0;
                    $insertData[] = $data;
                }
            }

            // エラーがあればロールバック＆エラー表示
            if (!empty($errors)) {
                DB::rollback();
                return redirect()->route('manage.managementuser.import')->with([
                    'import_errors' => $errors
                ]);
            }

            // データをまとめてDB反映
            foreach ($updateData as $updateRow) {
                DB::table('USERS')->where('USER_ID', $updateRow['USER_ID'])->update($updateRow['data']);
            }

            if (!empty($insertData)) {
                DB::table('USERS')->insert($insertData);
            }

            DB::commit();
            return redirect()->route('manage.managementuser.import')->with('success', 'インポートが完了しました。');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('manage.managementuser.import')->with('error', 'インポート中にエラーが発生しました。');
        }
    }




    public function import()
    {
        return view('manage.managementuser.import');
    }


    public function exportExec()
    {
        // データ取得
        $users = DB::table('USERS')->get();

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

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }


    public function detail($id)
    {
        $user = DB::table('USERS')->where('USER_ID', $id)->first();

        if (!$user) {
            abort(404, 'ユーザーが見つかりません');
        }

        $thuzaiin = DB::table('THUZAIIN')->where('USER_ID', $id)->first();

        // 都道府県リスト取得
        $prefectures = DB::table('GENERAL_CLASS')
                        ->orderBy('PREFECTURE_KEY')
                        ->get();

        return view('manage.managementuser.detail', compact('user', 'thuzaiin', 'prefectures'));
    }


    public function update(Request $request, $id)
    {
        $now = now();
        $currentUser = 'current_user';

        // バリデーション
        $request->validate([
            'NAME'  => 'required|string|max:255',
            'EMAIL' => 'required|email',
        ]);

        // USERSテーブルの更新
        DB::table('USERS')->where('USER_ID', $id)->update([
            'NAME'       => $request->input('NAME'),
            'EMAIL'      => $request->input('EMAIL'),
            'UPDATE_DT'  => $now,
            'UPDATE_USER'=> $currentUser
        ]);

        // 駐在員情報の登録・更新処理
        if ($request->has('is_thuzaiin')) {
            Thuzaiin::updateOrCreate(
                ['USER_ID' => $id],
                [
                    'POST_CODE1'   => $request->POST_CODE1 ?? '',
                    'POST_CODE2'   => $request->POST_CODE2 ?? '',
                    'PREF_ID'     => $request->THUZAIIN_PREF,
                    'ADDRESS1'    => $request->ADDRESS1,
                    'ADDRESS2'    => $request->ADDRESS2,
                    'ADDRESS3'    => $request->ADDRESS3,
                    'TEL'         => $request->TEL,
                    'DEL_FLG'     => 0,
                    'UPDATE_DT'   => $now,
                    'UPDATE_APP'  => 'Mops',
                    'UPDATE_USER' => $currentUser,
                    // 新規の場合用
                    'CREATE_DT'   => $now,
                    'CREATE_APP'  => 'Mops',
                    'CREATE_USER' => $currentUser,
                ]
            );
        } else {
            // チェックが外れていた場合、駐在員データを削除（論理削除も可）
            Thuzaiin::where('USER_ID', $id)->delete();
        }

        return redirect()->route('managementuser.index')->with('success', 'ユーザー情報を更新しました');
    }


    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('managementuser.index')->with('success', 'ユーザーを削除しました。');
    }


    public function exportConfirm()
    {
        return view('manage.managementuser.export');
    }

}
