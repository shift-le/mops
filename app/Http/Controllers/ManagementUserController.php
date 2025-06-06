<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $branchNames = DB::table('USERS')
            ->join('SOSHIKI1', 'USERS.SHITEN_BU_CODE', '=', 'SOSHIKI1.SHITEN_BU_CODE')
            ->distinct()->pluck('SOSHIKI1.SOSHIKI1_NAME');

        $officeNames = DB::table('USERS')
            ->join('SOSHIKI2', 'USERS.EIGYOSHO_GROUP_CODE', '=', 'SOSHIKI2.EIGYOSHO_GROUP_CODE')
            ->distinct()->pluck('SOSHIKI2.SOSHIKI2_NAME');

        return view('manage.managementuser.index', compact('users', 'branchNames', 'officeNames'));
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

        // 駐在員
        if ($request->filled('resident')) {
            $residentIds = DB::table('THUZAIIN')->pluck('USER_ID');
            $query->whereIn('USER_ID', $residentIds);
        }

        $sort = $request->input('sort', 'USER_ID');
        $order = $request->input('order', 'asc');

        $query->orderBy($sort, $order);

        $users = $query->paginate(15)->appends($request->all());

        $branchNames = DB::table('USERS')
            ->join('SOSHIKI1', 'USERS.SHITEN_BU_CODE', '=', 'SOSHIKI1.SHITEN_BU_CODE')
            ->distinct()->pluck('SOSHIKI1.SOSHIKI1_NAME');

        $officeNames = DB::table('USERS')
            ->join('SOSHIKI2', 'USERS.EIGYOSHO_GROUP_CODE', '=', 'SOSHIKI2.EIGYOSHO_GROUP_CODE')
            ->distinct()->pluck('SOSHIKI2.SOSHIKI2_NAME');

        return view('manage.managementuser.index', compact('users', 'branchNames', 'officeNames'));
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
        return view('manage.managementuser.create'); // 仮で空ビュー作成してOK
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
                'CREATE_APP'            => 'WebForm',
                'CREATE_USER'           => $currentUser,
                'UPDATE_DT'             => $now,
                'UPDATE_APP'            => 'WebForm',
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
                    'CREATE_APP'  => 'WebForm',
                    'CREATE_USER' => $currentUser,
                    'UPDATE_DT'   => $now,
                    'UPDATE_APP'  => 'WebForm',
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
        // バリデーション
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('import_file');

        // スプレッドシートの読み込み
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true); // A,B,C...のキーで配列化

        // トランザクション開始
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                if ($index === 1) {
                    continue;
                }

                if (empty($row['A'])) {
                    continue;
                }

                $userId = $row['A'];

                // 既存レコード取得
                $existingUser = DB::table('USERS')->where('USER_ID', $userId)->first();

                // 更新・登録用データ配列
                $data = [
                    'UPDATE_FLG' => '1',
                    'UPDATE_DT' => Carbon::now(),
                    'UPDATE_APP' => 'ExcelImport',
                    'UPDATE_USER' => 'import_user'
                ];

                // Excel側に値があればセット
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
                    // UPDATEのみ記述あるものだけ更新
                    DB::table('USERS')
                        ->where('USER_ID', $userId)
                        ->update($data);
                } else {
                    // INSERT用データ追加
                    $data['USER_ID'] = $userId;
                    $data['PASSWORD'] = $row['C'] ?? '';
                    $data['DEL_FLG'] = 0;

                    DB::table('USERS')->insert($data);
                }
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

        return view('manage.managementuser.detail', compact('user'));
    }


    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'NAME' => 'required|string|max:255',
            'EMAIL' => 'required|email',
            // 必要な項目を追加
        ]);

        // 更新処理
        DB::table('USERS')->where('USER_ID', $id)->update([
            'NAME' => $request->input('NAME'),
            'EMAIL' => $request->input('EMAIL'),
            'UPDATE_DT' => now(),
            'UPDATE_USER' => 'current_user'
        ]);

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
