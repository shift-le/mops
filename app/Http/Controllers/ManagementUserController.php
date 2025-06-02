<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User; // 仮データ用モデル（今は使わない）

// use App\Models\ManagementUser; // 将来のDB用モデル（今は使わない）

class ManagementUserController extends Controller
{
    public function index(Request $request)
    {
        // クエリパラメータの取得
        $user = $request->query('user');
        $sort = $request->query('sort', 'USER_ID'); // デフォルトのソートカラム
        $order = $request->query('order', 'asc');   // デフォルトのソート順

        // クエリビルダでUSERSテーブルから取得
        $query = DB::table('USERS')
            ->select(
                'USER_ID',
                'NAME',
                'NAME_KANA',
                'EMAIL',
                'SHITEN_BU_CODE',
                'CREATE_DT'
            );

        // 氏名での絞り込み（部分一致）
        if (!empty($user)) {
            $query->where('NAME', 'like', "%{$user}%");
        }

        // ソート
        $query->orderBy($sort, $order);

        // ページネーション（1ページ15件）
        $users = $query->paginate(15);

        $branches = User::select('SHITEN_BU_CODE')
            ->distinct()
            ->whereNotNull('SHITEN_BU_CODE')
            ->pluck('SHITEN_BU_CODE');
        $branchNames = DB::table('USERS')
            ->join('SOSHIKI1', 'USERS.SHITEN_BU_CODE', '=', 'SOSHIKI1.SHITEN_BU_CODE')
            ->distinct()
            ->pluck('SOSHIKI1.SOSHIKI1_NAME');
        $offices = User::select('EIGYOSHO_GROUP_CODE')
            ->distinct()
            ->whereNotNull('EIGYOSHO_GROUP_CODE')
            ->pluck('EIGYOSHO_GROUP_CODE');
        $officeNames = DB::table('USERS')
            ->join('SOSHIKI2', 'USERS.EIGYOSHO_GROUP_CODE', '=', 'SOSHIKI2.EIGYOSHO_GROUP_CODE')
            ->distinct()
            ->pluck('SOSHIKI2.SOSHIKI2_NAME');
        // viewに渡す
        return view('manage.managementuser.index', compact('users','branches','offices', 'branchNames', 'officeNames'));
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
        DB::table('USERS')->insert([
            'USER_ID'        => $request->USER_ID,
            'SHAIN_ID'       => $request->SHAIN_ID,
            'NAME'      => $request->NAME,
            'NAME_KANA' => $request->NAME_KANA,
            'PASSWORD'       => $request->SHAIN_ID,
            'EMAIL'          => $request->EMAIL,
            'MOBILE_TEL'          => $request->MOBILE_TEL,
            'MOBILE_EMAIL'    => $request->MOBILE_EMAIL,
            'SHITEN_BU_CODE' => $request->SHITEN_BU_CODE,
            'EIGYOSHO_GROUP_CODE' => $request->EIGYOSHO_GROUP_CODE,
            'ROLE_ID'        => $request->ROLE_ID,
            'UPDATE_FLG'     => 1,
            'DEL_FLG'        => 0,
            'CREATE_DT'      => now(),
            'CREATE_APP'     => 'WebForm',
            'CREATE_USER'    => 'current_user',
            'UPDATE_DT'      => now(),
            'UPDATE_APP'     => 'WebForm',
            'UPDATE_USER'    => 'current_user',
        ]);

        return redirect()->route('managementuser.index')->with('message', 'ユーザーを登録しました');
    }


    public function import()
    {
        return view('manage.managementuser.import');
    }

    // public function import(Request $request)
    // {
    //     // バリデーション
    //     $request->validate([
    //         'import_file' => 'required|file|mimes:xlsx,xls',
    //     ]);

    //     $file = $request->file('import_file');

    //     // スプレッドシートの読み込み
    //     $spreadsheet = IOFactory::load($file->getRealPath());
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $rows = $sheet->toArray(null, true, true, true); // A,B,C...のキーで配列化

    //     // トランザクション開始
    //     DB::beginTransaction();

    //     try {
    //         // 2行目からデータを処理（1行目はヘッダ行のため）
    //         foreach ($rows as $index => $row) {
    //             if ($index === 1) {
    //                 continue;
    //             }

    //             // 空行ならスキップ
    //             if (empty($row['A'])) {
    //                 continue;
    //             }

    //             // データ取得
    //             $userId = $row['A'];
    //             $shainId = $row['C'];
    //             $name = $row['D'];
    //             $nameKana = $row['E'];
    //             $email = $row['G'];
    //             $mobileTel = $row['H'];
    //             $mobileEmail = $row['I'];
    //             $shitenBuCode = $row['K'];
    //             $eigyoshoGroupCode = $row['L'];
    //             $roleId = $row['N'];

    //             // INSERT or UPDATE
    //             DB::table('USERS')->updateOrInsert(
    //                 ['USER_ID' => $shainId],
    //                 [
    //                     'SHAIN_ID' => $shainId,
    //                     'NAME' => $name,
    //                     'NAME_KANA' => $nameKana,
    //                     'PASSWORD' => $shainId,
    //                     'EMAIL' => $email,
    //                     'MOBILE_TEL' => $mobileTel,
    //                     'MOBILE_EMAIL' => $mobileEmail,
    //                     'SHITEN_BU_CODE' => $shitenBuCode,
    //                     'EIGYOSHO_GROUP_CODE' => $eigyoshoGroupCode,
    //                     'ROLE_ID' => $roleId,
    //                     'DEL_FLG' => 0,
    //                     'UPDATE_FLG' => '1',
    //                     'UPDATE_DT' => Carbon::now(),
    //                     'UPDATE_APP' => 'ExcelImport',
    //                     'UPDATE_USER' => 'import_user'
    //                 ]
    //             );
    //         }

    //         // コミット
    //         DB::commit();

    //         return redirect()->route('manage.managementuser.import')->with('success', 'インポートが完了しました。');

    //     } catch (\Exception $e) {
    //         // ロールバック
    //         DB::rollback();



    //         return redirect()->route('manage.managementuser.import')->with('error', 'インポート中にエラーが発生しました。');
    //     }
    // }



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
