@extends('layouts.manage')

@section('page_title', 'ツール情報管理')

@section('content')
<style>
.pagination-wrapper svg {
    width: 20px !important;
    height: 20px !important;
}
.pagination-wrapper .flex {
    justify-content: center;
}
.pagination-wrapper .page-link {
    padding: 4px 8px;
    margin: 0 2px;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}
.pagination-wrapper .page-link:hover {
    background-color: #f0f0f0;
}
.pagination-wrapper .active .page-link {
    font-weight: bold;
    background-color: #007bff;
    color: #fff;
}
</style>

<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementtool.import') }}" class="tab-button {{ request()->routeIs('managementtool.import') ? 'active' : '' }}">インポート</a>
    </div>
</div>

<div class="search-box">
    <form method="GET" action="{{ route('managementtool.index') }}">
        <div style="background-color:rgb(76, 133, 247); padding: 10px; border-radius: 6px; color: #fff; margin-bottom: 16px;">
            <div style="margin-bottom: 8px;">
                <input type="text" name="TOOL" value="{{ request('TOOL') }}" placeholder="キーワード" class="text-input" style="width: 100%; max-width: 600px;">
            </div>
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <label>　検索対象</label>
                <label><input type="checkbox" name="search_target[]" value="TOOL_CODE" {{ in_array('TOOL_CODE', request()->input('search_target', [])) ? 'checked' : '' }}> ツールコード</label>
                <label><input type="checkbox" name="search_target[]" value="TOOL_NAME" {{ in_array('TOOL_NAME', request()->input('search_target', [])) ? 'checked' : '' }}> ツール名</label>
                <label><input type="checkbox" name="search_target[]" value="TOOL_NAME_KANA" {{ in_array('TOOL_NAME_KANA', request()->input('search_target', [])) ? 'checked' : '' }}> ツール名カナ</label>
            </div>
        </div>

        <div class="form-row">
            <select name="RYOIKI" class="select-input">
                <option value="">領域</option>
                @foreach($ryoiki as $id => $name)
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

        <div class="form-row">
            <label>ステータス</label>
            @for($i = 0; $i <= 4; $i++)
                <label><input type="radio" name="TOOL_STATUS" value="{{ $i }}" {{ request('TOOL_STATUS') == $i ? 'checked' : ($i==0 && request('TOOL_STATUS') === null ? 'checked' : '') }}> {{ ['表示','仮登録','マルホ確認済み','中島準備完了','非表示'][$i] }}</label>
            @endfor
        </div>

        <div class="form-row">
            <label>表示期間</label>
            <input type="date" name="MOPS_START_DATE" value="{{ request('MOPS_START_DATE') }}" class="date-input">
            <span>〜</span>
            <input type="date" name="MOPS_END_DATE" value="{{ request('MOPS_END_DATE') }}" class="date-input">
        </div>

        <div class="form-row">
            <label>Mops登録日</label>
            <input type="date" name="MOPS_ADD_DATE_FROM" value="{{ request('MOPS_ADD_DATE_FROM') }}" class="date-input">
            <span>〜</span>
            <input type="date" name="MOPS_ADD_DATE_TO" value="{{ request('MOPS_ADD_DATE_TO') }}" class="date-input">
        </div>

        <hr>

        <div class="form-row btn-row">
            <a href="{{ route('managementtool.index') }}" class="btn-clear">検索条件をクリア</a>
            <button type="submit" class="submit">検索する</button>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('managementtool.NoticeStatus') }}">
    @csrf
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
        <p style="margin: 0;">ステータス変更</p>
        <select name="TOOL_STATUS" required style="padding: 4px; font-size: 0.9rem;">
            <option value="">ステータス選択</option>
            <option value="0">表示</option>
            <option value="1">仮登録</option>
            <option value="2">マルホ確認済み</option>
            <option value="3">中島準備完了</option>
            <option value="4">非表示</option>
        </select>
        <button type="submit" class="submit" style="padding: 6px 12px; font-size: 0.9rem;background: #007bff; color: #fff;">チェックしたツールを中島に通知する</button>
        <label style="margin-left: auto; display: flex; align-items: center; gap: 6px;">
            表示件数：
            <select name="per_page" onchange="this.form.submit()" style="padding: 4px; font-size: 0.9rem;">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10件</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50件</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100件</option>
            </select>
        </label>
    </div>

    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th><input type="checkbox" id="select_all" style="width: 20px; height: 20px;"></th>
                <th>ツール名</th>
                <th>ツールコード</th>
                <th>ステータス</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="background-color:#fff">
            @foreach ($tools as $tool)
                <tr>
                    <td><input type="checkbox" name="selected_tools[]" value="{{ $tool->TOOL_CODE }}" class="tool_checkbox" style="width: 20px; height: 20px;"></td>
                    <td><a href="{{ route('managementtool.show', ['id' => $tool->TOOL_CODE]) }}">{{ $tool->TOOL_NAME ?? '不明' }}</a></td>
                    <td><input type="text" value="{{ $tool->TOOL_CODE ?? '' }}" readonly style="width: 100%; border: none; background: transparent;"></td>
                    <td><input type="text" value="@switch($tool->TOOL_STATUS)@case(0) 表示 @break @case(1) 仮登録 @break @case(2) マルホ確認済み @break @case(3) 中島準備完了 @break @case(4) 非表示 @break @default 不明 @endswitch" readonly style="width:100%;border:none;background:transparent;"></td>
                    <td style="display: flex; gap: 6px; align-items: right;">
                        <a href="{{ route('managementtool.show', ['id' => $tool->TOOL_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #fff; color: #007bff; border-radius: 4px; text-decoration: none;">詳細</a>
                        <form action="{{ route('managementtool.delete', ['id' => $tool->TOOL_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #fff; color: #dc3545; border-radius: 4px;">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>

<div style="margin-top: 20px;">
    {{ $tools->links('components.numeric') }}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 「すべて選択」チェックボックス制御
    const selectAll = document.getElementById('select_all');
    const checkboxes = document.querySelectorAll('.tool_checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // カレンダー外クリックでフォーカス解除（カレンダー閉じる）
    document.addEventListener('click', function (event) {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            if (document.activeElement === input && !input.contains(event.target)) {
                setTimeout(() => input.blur(), 100);
            }
        });
    });

    // Safari対策: フォーカスアウト時に blur を強制（ダブルトリガー）
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('focus', () => {
            setTimeout(() => {
                if (document.activeElement === input) {
                    input.blur();
                }
            }, 500); // Safari は遅延があるため長めに
        });
    });
});
</script>
@endsection

