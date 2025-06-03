<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool; // 将来のDB用モデル（今は使わない）
use App\Models\Ryoiki; // 領域モデルをインポート
use App\Models\Hinmei; // 品名モデルをインポート
use App\Models\Keijiban; // 掲示板モデルをインポート
use App\Models\Faq; // FAQモデルをインポート
use App\Models\User; // ユーザーモデルをインポート

class ManagementToolController extends Controller
{
    public function index() { /* 一覧 */
        $tools = Tool::all();
        $ryoiki = '領域名'; // 仮の値（本当はDBから取るなど）
        $hinmei = '品名名'; // 仮の値
        $branches = User::select('SHITEN_BU_CODE')
            ->distinct()
            ->whereNotNull('SHITEN_BU_CODE')
            ->pluck('SHITEN_BU_CODE');

        $ryoikis = Ryoiki::pluck('RYOIKI_NAME', 'RYOIKI_CODE');
        $hinmeis = Hinmei::pluck('HINMEI_NAME', 'HINMEI_CODE');

        return view('manage.managementtool.index', compact('tools', 'ryoiki', 'hinmei', 'ryoikis', 'hinmeis', 'branches'));
    }


    public function detail($id)
    {
        $tool = Tool::find($id);

        if (!$tool) {
            abort(404);
        }

        return view('managementtool.detail', compact('tool'));
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


    public function show($id) { /* 詳細 */
        $tool = []; // ここにツールの詳細を取得するロジックを追加
        return view('manage.managementtool.show', compact('tool'));
    }


    public function delete($id) { /* 削除 */
        // ツールの削除ロジックを追加
        return redirect()->route('managementtool.index')->with('success', 'Tool deleted successfully.');
    }

    
    public function import() { /* インポート */ 
        return view('manage.managementtool.import');
    }

}
