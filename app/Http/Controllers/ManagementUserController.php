<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// use App\Models\ManagementUser; // 将来のDB用モデル（今は使わない）

class ManagementUserController extends Controller
{
    public function index(Request $request)
    {
        // クエリパラメータの取得
        $user = $request->query('user');
        $sort = $request->query('sort', 'USER_ID'); // デフォルトのソートカラム
        $order = $request->query('order', 'asc');   // デフォルトのソート順

        // 仮データ
        $users = [
            ['USER_ID' => 0001, 'USER_NAME' => 'ユーザー1', 'NAME_KANA'=>'ユーザー1', 'EMAIL' => 'user1@example.com','SHITEN_BU_CODE' => 'B001', 'CREATE_DT' => '2025/01/01'],
            ['USER_ID' => 0002, 'USER_NAME' => 'ユーザー2', 'NAME_KANA'=>'ユーザー2', 'EMAIL' => 'user2@example.com','SHITEN_BU_CODE' => 'B002', 'CREATE_DT' => '2025/01/02'],
            ['USER_ID' => 0003, 'USER_NAME' => 'ユーザー3', 'NAME_KANA'=>'ユーザー3', 'EMAIL' => 'user3@example.com','SHITEN_BU_CODE' => 'B003', 'CREATE_DT' => '2025/01/03'],
            ['USER_ID' => 0004, 'USER_NAME' => 'ユーザー4', 'NAME_KANA'=>'ユーザー4', 'EMAIL' => 'user4@example.com','SHITEN_BU_CODE' => 'B004', 'CREATE_DT' => '2025/01/04'],
            ['USER_ID' => 0005, 'USER_NAME' => 'ユーザー5', 'NAME_KANA'=>'ユーザー5', 'EMAIL' => 'user5@example.com','SHITEN_BU_CODE' => 'B005', 'CREATE_DT' => '2025/01/05'],
            // 必要ならここにさらに仮データ追加
        ];

        // ソート実行（コレクション化してソート）
        $users = collect($users)
            ->when($user, function ($query) use ($user) {
                return $query->where('USER_NAME', $user);
            })
            ->sortBy($sort, SORT_REGULAR, $order === 'desc')
            ->values()
            ->all();

            // $users = USERS::paginate(15);
        return view('manage.managementuser.index', compact('users'));

        // // DB接続時用
        // $users = ManagementUser::where('USER_NAME', 'like', "%{$user}%")
        //     ->orderBy($sort, $order)
        //     ->paginate(15);
    }

    public function show($id)
    {
        // 仮データ
        $users = [
            ['USER_ID' => 0001, 'USER_NAME' => 'ユーザー1', 'NAME_KANA'=>'ユーザー1', 'EMAIL' => 'user1@example.com','SHITEN_BU_CODE' => 'B002', 'CREATE_DT' => '2025/01/01'],
            ['USER_ID' => 2, 'USER_NAME' => 'ユーザー2', 'NAME_KANA'=>'ユーザー2', 'EMAIL' => 'user2@example.com','SHITEN_BU_CODE' => 'B003', 'CREATE_DT' => '2025/01/02'],
            ['USER_ID' => 3, 'USER_NAME' => 'ユーザー3', 'NAME_KANA'=>'ユーザー3', 'EMAIL' => 'user3@example.com','SHITEN_BU_CODE' => 'B004', 'CREATE_DT' => '2025/01/03'],
            // 必要ならさらに追加
        ];

        // 指定IDのユーザー取得
        $user = collect($users)->firstWhere('USER_ID', (int)$id);

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('manage.managementuser.show', compact('user'));

        // // DB接続時用
        // $user = ManagementUser::findOrFail($id);
    }
        public function create()
    {
        return view('manage.managementuser.create'); // 仮で空ビュー作成してOK
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

    //         return redirect()->route('managementuser.import')->with('success', 'インポートが完了しました。');

    //     } catch (\Exception $e) {
    //         // ロールバック
    //         DB::rollback();



    //         return redirect()->route('managementuser.import')->with('error', 'インポート中にエラーが発生しました。');
    //     }
    // }

    public function export()
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
        $user = User::findOrFail($id);
        return view('managementuser.detail', compact('user'));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('managementuser.index')->with('success', 'ユーザーを削除しました。');
    }

}
