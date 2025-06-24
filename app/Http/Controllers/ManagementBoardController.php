<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 認証用ファサード
use Illuminate\Support\Facades\Log; // ログ出力用ファサード
use Illuminate\Support\Facades\Storage;
use App\Models\Keijiban; // 将来のDB用モデル（今は使わない）
use App\Models\KeijibanTemp;

class ManagementBoardController extends Controller
{
    public function index(Request $request)
    {
        // モックデータ（DB接続→コメントアウト）
        $board = $request->query('keijiban');
        $sort = $request->query('sort', 'JUYOUDO_STATUS'); // デフォルトのソートカラム
        $order = $request->query('order', 'asc');   // デフォルトのソート順
        // クエリビルダでUSERSテーブルから取得
        $query = DB::table('KEIJIBAN')
            ->select(
                'KEIJIBAN_CODE',
                'JUYOUDO_STATUS',
                'KEISAI_START_DATE',
                'KEIJIBAN_TITLE',
                'KEIJIBAN_CATEGORY',
                'HYOJI_FLG'  // ★これ追加する！
            )
            ->where('DEL_FLG', 0)
            ->orderBy($sort, $order);

        // ソート
        $query->orderBy($sort, $order);
        // ページネーション（1ページ15件）
        $posts = $query->paginate(15);

        if (session()->has('attachment_paths')) {
            foreach (session('attachment_paths') as $tmpPath) {
                Storage::delete($tmpPath);
            }
            session()->forget('attachment_paths');
        }


        // ログ出力
        Log::debug('【管理】掲示板一覧取得', [
            'method_name' => __METHOD__,
            'http_method' => $request->method(),
            'sort' => $sort,
            'order' => $order,
            'posts_count' => $posts->count(),
        ]);

        return view('manage.managementboard.index', compact('posts', 'board', 'sort', 'order'));
    }


    public function show($id)
    {
        try {
            $board = DB::table('KEIJIBAN')->where('KEIJIBAN_CODE', $id)->first();

            if (!$board) {
                abort(404, 'KEIJIBAN not found');
            }

            // ログ出力
            Log::debug('【管理】掲示板詳細取得', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'KEIJIBAN_CODE' => $id,
                'board' => $board,
            ]);

            return view('manage.managementboard.show', compact('board'));

        } catch (\Exception $e) {
            // エラーログ出力
            Log::error('【管理】掲示板詳細取得エラー', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'KEIJIBAN_CODE' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 任意のエラー画面にリダイレクト、またはエラーページへ
            return redirect()->back()->with('error', '掲示板詳細の取得中にエラーが発生しました。');
        }
    }



    public function create()
    {
        if (session()->has('attachment_paths')) {
            foreach (session('attachment_paths') as $tmpPath) {
                Storage::delete($tmpPath);
            }
            session()->forget('attachment_paths');
        }
        Log::debug('【管理】掲示板新規作成画面表示');
        
        // 新規作成画面の表示
        return view('manage.managementboard.create');
    }


    public function confirm(Request $request)
    {
        try {
            // バリデーション
            $request->validate([
                'JUYOUDO_STATUS' => 'required|integer',
                'KEISAI_START_DATE' => 'required|date',
                'KEIJIBAN_TITLE' => 'required|string|max:255',
                'KEIJIBAN_TEXT' => 'required|string',
                'KEIJIBAN_CATEGORY' => 'required|integer',
                'HYOJI_FLG' => 'required|boolean',
                'mode' => 'required|string|in:edit,create',
            ]);

            $paths = [];
            if ($request->hasFile('attachment')) {
                $files = $request->file('attachment');
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file && $file->isValid()) {
                            $path = $file->store('keijiban_tmp', 'shared');
                            $paths[] = $path;
                        }
                    }
                }
            }
            session()->put('attachment_paths', $paths);


            // 確認画面表示用のログ
            Log::debug('【管理】掲示板新規作成確認画面表示', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'data' => $request->all(),
                'attachments' => $paths,
            ]);

            // 確認画面へ
            return view('manage.managementboard.confirm', [
                'data' => $request->all(),
                'attachments' => $paths,  // これも渡せばbladeで確認画面に表示できる
            ]);

        } catch (\Exception $e) {
            Log::error('【管理】掲示板新規作成確認画面表示エラー', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', '掲示板確認画面の表示中にエラーが発生しました。');
        }
    }



    public function store(Request $request)
    {
        try {
            // 最大の番号取得
            $maxCode = DB::table('KEIJIBAN')
                ->select(DB::raw('MAX(CAST(SUBSTRING(KEIJIBAN_CODE, 3) AS UNSIGNED)) as max_code'))
                ->value('max_code');

            // 次の番号作成
            $nextCodeNum = $maxCode ? $maxCode + 1 : 1;
            $nextCode = 'KB' . str_pad($nextCodeNum, 4, '0', STR_PAD_LEFT);

            // バリデーション
            $request->validate([
                'JUYOUDO_STATUS' => 'required|integer',
                'KEISAI_START_DATE' => 'required|date',
                'KEISAI_END_DATE' => 'required|date',
                'KEIJIBAN_TITLE' => 'required|string|max:255',
                'KEIJIBAN_TEXT' => 'required|string',
                'KEIJIBAN_CATEGORY' => 'required|integer',
                'HYOJI_FLG' => 'required|boolean',
            ]);

            // 掲示板本体保存
            DB::table('KEIJIBAN')->insert([
                'KEIJIBAN_CODE' => $nextCode,
                'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                'KEISAI_END_DATE' => $request->input('KEISAI_END_DATE'),
                'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                'KEIJIBAN_TEXT' => $request->input('KEIJIBAN_TEXT'),
                'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
                'HYOJI_FLG' => $request->input('HYOJI_FLG'),
                'DEL_FLG' => 0,
                'CREATE_DT' => now(),
                'CREATE_APP' => 'Mops',
                'CREATE_USER' => '管理者',
                'UPDATE_DT' => now(),
                'UPDATE_APP' => 'Mops',
                'UPDATE_USER' => '管理者',
            ]);
            Log::debug('【管理】掲示板新規作成', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'KEIJIBAN_CODE' => $nextCode,
                'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
            ]);
            // 添付ファイルの処理
            if (session()->has('attachment_paths')) {
                $fileNo = 1;
                foreach (session('attachment_paths') as $tmpPath) {
                    $filename = basename($tmpPath);
                    $newPath = 'keijiban/' . $filename;

                    // 👇 disk('shared') に統一する
                    if (Storage::disk('shared')->exists($tmpPath)) {
                        $moved = Storage::disk('shared')->move($tmpPath, $newPath);
                        if ($moved) {
                            DB::table('KEIJIBAN_TEMP')->insert([
                                'KEIJIBAN_CODE' => $nextCode,
                                'FILE_NO'       => $fileNo++,
                                'FILE_NAME'     => $filename,
                                'CREATE_DT'     => now(),
                                'CREATE_APP'    => 'Mops',
                                'CREATE_USER'   => '管理者',
                                'UPDATE_DT'     => now(),
                                'UPDATE_APP'    => 'Mops',
                                'UPDATE_USER'   => '管理者',
                            ]);
                            Log::debug('【管理】掲示板添付ファイル保存', [
                                'method_name' => __METHOD__,
                                'http_method' => $request->method(),
                                'KEIJIBAN_CODE' => $nextCode,
                                'FILE_NO' => $fileNo - 1,
                                'FILE_NAME' => $filename,
                            ]);
                        } else {
                            Log::error('ファイルの移動に失敗', ['tmpPath' => $tmpPath]);
                        }
                    } else {
                        Log::error('仮ファイルが存在しない', ['tmpPath' => $tmpPath]);
                    }
                }
                session()->forget('attachment_paths');
            }


            Log::debug('【管理】掲示板新規作成', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'KEIJIBAN_CODE' => $nextCode,
                'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
            ]);

            return redirect()->route('managementboard.index')->with('success', '掲示板が作成されました。');
        } catch (\Exception $e) {
            Log::error('【管理】掲示板新規作成エラー', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', '掲示板の作成中にエラーが発生しました。');
        }
    }




    public function delete($id)
    {
        try {
            // 削除処理
            DB::table('KEIJIBAN')
                ->where('KEIJIBAN_CODE', $id)
                ->update([
                    'DEL_FLG' => 1
                ]);

            // ログ出力
            Log::debug('【管理】掲示板削除', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'KEIJIBAN_CODE' => $id,
            ]);

            return redirect()->route('managementboard.index')->with('success', '掲示板項目を削除しました。');

        } catch (\Exception $e) {
            // エラーログ出力
            Log::error('【管理】掲示板削除エラー', [
                'method_name' => __METHOD__,
                'http_method' => request()->method(),
                'KEIJIBAN_CODE' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', '掲示板の削除中にエラーが発生しました。');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // バリデーション
            $request->validate([
                'JUYOUDO_STATUS' => 'required|integer',
                'KEISAI_START_DATE' => 'required|date',
                'KEIJIBAN_TITLE' => 'required|string|max:255',
                'KEIJIBAN_TEXT' => 'required|string',
                'KEIJIBAN_CATEGORY' => 'required|integer',
                'HYOJI_FLG' => 'required|boolean',
            ]);

            // 更新処理
            DB::table('KEIJIBAN')
                ->where('KEIJIBAN_CODE', $id)
                ->update([
                    'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                    'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                    'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                    'KEIJIBAN_TEXT' => $request->input('KEIJIBAN_TEXT'),
                    'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
                    'HYOJI_FLG' => $request->input('HYOJI_FLG'),
                    'UPDATE_DT' => now(),
                    'UPDATE_APP' => 'WebApp',
                    'UPDATE_USER' => '管理者',
                ]);

            // 成功ログ
            Log::debug('【管理】掲示板更新', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'KEIJIBAN_CODE' => $id,
                'JUYOUDO_STATUS' => $request->input('JUYOUDO_STATUS'),
                'KEISAI_START_DATE' => $request->input('KEISAI_START_DATE'),
                'KEIJIBAN_TITLE' => $request->input('KEIJIBAN_TITLE'),
                'KEIJIBAN_CATEGORY' => $request->input('KEIJIBAN_CATEGORY'),
            ]);

            return redirect()->route('managementboard.index')->with('success', '掲示板内容を更新しました。');

        } catch (\Exception $e) {
            // エラーログ
            Log::error('【管理】掲示板更新エラー', [
                'method_name' => __METHOD__,
                'http_method' => $request->method(),
                'KEIJIBAN_CODE' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->withInput()->with('error', '掲示板の更新中にエラーが発生しました。');
        }
    }
}
