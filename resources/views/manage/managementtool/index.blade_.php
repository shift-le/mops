<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/manage.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>
@extends('layouts.manage')

@section('content')
    <!-- タブボタンボックス -->
    <div class="tab-wrapper">
        <div class="tab-container">
            <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
            <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
            <a href="{{ route('managementtool.import') }}" class="tab-button {{ request()->routeIs('managementtool.import') ? 'active' : '' }}">インポート</a>
        </div>
    </div>
    <!-- 検索フォーム -->
    <div class="search-box">
        <form method="GET" action="{{ route('managementtool.index') }}">

            {{-- キーワード＋対象カラム --}}
            <div style="background-color:rgb(76, 133, 247); padding: 10px; border-radius: 6px; color: #fff; margin-bottom: 16px;">
                {{-- 上段：キーワード入力 --}}
                <div style="margin-bottom: 8px;">
                    <input type="text" name="TOOL" value="{{ request('TOOL') }}" placeholder="キーワード" class="text-input" style="width: 100%; max-width: 600px;">
                </div>

                {{-- 下段：チェックボックス3つ --}}
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <label>　検索対象</label>
                    <label><input type="checkbox" name="search_target[]" value="TOOL_CODE" {{ in_array('TOOL_CODE', request()->input('search_target', [])) ? 'checked' : '' }}> ツールコード</label>
                    <label><input type="checkbox" name="search_target[]" value="TOOL_NAME" {{ in_array('TOOL_NAME', request()->input('search_target', [])) ? 'checked' : '' }}> ツール名</label>
                    <label><input type="checkbox" name="search_target[]" value="TOOL_NAME_KANA" {{ in_array('TOOL_NAME_KANA', request()->input('search_target', [])) ? 'checked' : '' }}> ツール名カナ</label>
                </div>
            </div>


            {{-- 領域・品名・支店部 --}}
            <div class="form-row">
                <select name="RYOIKI" class="select-input">
                    <option value="">領域</option>
                    @foreach($ryoikis as $id => $name)
                        <option value="{{ $id }}" {{ request('RYOIKI') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="HINMEI" class="select-input">
                    <option value="">品名</option>
                    @foreach($hinmeis as $id => $name)
                        <option value="{{ $id }}" {{ request('HINMEI') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ステータス --}}
            <div class="form-row">
                <label>ステータス</label>
                @for($i = 0; $i <= 4; $i++)
                    <label><input type="radio" name="TOOL_STATUS" value="{{ $i }}" {{ request('TOOL_STATUS') == $i ? 'checked' : ($i==0 && request('TOOL_STATUS') === null ? 'checked' : '') }}> {{ ['表示','仮登録','マルホ確認済み','中島準備完了','非表示'][$i] }}</label>
                @endfor
            </div>

            {{-- 表示期間 --}}
            <div class="form-row">
                <label>表示期間</label>
                <input type="date" name="NEW_DISPLAY_START_DATE" value="{{ request('NEW_DISPLAY_START_DATE') }}" class="date-input">
                <span>〜</span>
                <input type="date" name="NEW_DISPLAY_END_DATE" value="{{ request('NEW_DISPLAY_END_DATE') }}" class="date-input">
            </div>

            {{-- Mops登録日 --}}
            <div class="form-row">
                <label>Mops登録日</label>
                <input type="date" name="create_dt_from" value="{{ request('create_dt_from') }}" class="date-input">
                <span>〜</span>
                <input type="date" name="create_dt_to" value="{{ request('create_dt_to') }}" class="date-input">
            </div>
            <hr>

            {{-- ボタンエリア --}}
            <div class="form-row btn-row">
                <a href="{{ route('managementtool.index') }}" class="btn-clear">検索条件をクリア</a>
                <button type="submit" class="submit">検索する</button>
            </div>
        </form>
    </div>

    <div>
        <form method="GET" action="{{ url()->current() }}" style="display: inline;">
            {{-- 検索条件が他にもあるならここに hidden 追加 --}}
            <label style="margin-left: 20px;">
                表示件数：
                <select name="per_page" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10件</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50件</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100件</option>
                </select>
            </label>
        </form>


    <form method="POST" action="{{ route('managementtool.NoticeStatus') }}" id="Notice">
        @csrf

        <div>
            <p>ステータス変更</p>
        </div>

        <div style="margin: 10px 0;">
            <select name="TOOL_STATUS" id="statusSelect" required>
                <option value="">ステータス選択</option>

                @php
                    $user = Auth::user();
                    $role = $user->ROLE_ID ?? null;

                    // 画面上で何を選べるか
                    $allowedStatus = [];

                    if ($role === 'MA01') {
                        $allowedStatus = [2, 0]; // 1→2 / 3→0 可能性を想定
                    } elseif ($role === 'NA01') {
                        $allowedStatus = [3]; // 2→3
                    }
                @endphp

                @foreach ([0=>'表示', 1=>'仮登録', 2=>'マルホ確認済み', 3=>'中島準備完了', 4=>'非表示'] as $val => $label)
                    @if(in_array($val, $allowedStatus))
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endif
                @endforeach
            </select>

            <button type="submit" class="submit">チェックしたツールを通知する</button>
        </div>

        {{-- モーダル --}}
        <div id="statusModal" style="display:none; background: rgba(0,0,0,0.6); position:fixed; top:0; left:0; width:100%; height:100%;">
            <div style="background:white; padding:20px; max-width:400px; margin:100px auto; border-radius:5px;">
                <p>ステータスが混在しています。修正してください。</p>
                <button onclick="document.getElementById('statusModal').style.display='none'">閉じる</button>
            </div>
        </div>
    </div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>
                    <input type="checkbox" id="select_all" style="width: 20px; height: 20px;">
                </th>
                <th>ツール名</th>
                <th>ツールコード</th>
                <th>ステータス</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="background-color:#fff">
            @foreach ($tools as $tool)
                <tr>
                    <td>
                        <input type="checkbox" name="selected_tools[]" value="{{ $tool->TOOL_CODE }}" class="tool_checkbox" data-status="{{ $tool->TOOL_STATUS }}" style="width: 20px; height: 20px;">
                    </td>　　　　　
                    <td>
                        <a href="{{ route('managementtool.show', ['id' => $tool->TOOL_CODE]) }}">{{ $tool->TOOL_NAME ?? '不明' }}</a>
                    </td>
                    <td>
                        <input type="text" value="{{ $tool->TOOL_CODE ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <input type="text" value="{{ $statusLabels[$tool->TOOL_STATUS] ?? '不明' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td style="display: flex; gap: 6px; align-items: right;">
                        <a href="{{ route('managementtool.show', ['id' => $tool->TOOL_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #fff; color: #007bff; border-radius: 4px; text-decoration: none;">詳細</a>
                        <form action="{{ route('managementtool.delete', ['id' => $tool->TOOL_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #fff; color: #dc3545; border-radius: 4px; ">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </form>

    <div style="margin-top: 20px;">
        {{ $tools->appends(request()->except('page'))->links() }}
    </div>



@endsection

<script>
document.getElementById('NoticeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // チェックされた行のTOOL_STATUSを取得
    const checkedTools = document.querySelectorAll('input[name="selected_tools[]"]:checked');
    const statuses = new Set();

    checkedTools.forEach(tool => {
        const status = tool.getAttribute('data-status');
        if (status) {
            statuses.add(status);
        }
    });

    if (statuses.size > 1) {
        // ステータスが混在している場合
        document.getElementById('statusModal').style.display = 'block';
        return false;
    }

    // 問題なければフォーム送信
    this.submit();
});
</script>

