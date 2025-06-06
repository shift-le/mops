@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementtool.import') }}" class="tab-button">インポート</a>
    </div>
</div>

<h2>ツール情報 編集</h2>

<form method="POST" action="{{ route('managementtool.update', ['id' => $tool->TOOL_CODE]) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="content-box">
        <h3>基本情報</h3>

        <div class="form-row">
            <label>ステータス</label>
            <label><input type="radio" name="TOOL_STATUS" value="0" {{ $tool->TOOL_STATUS == 0 ? 'checked' : '' }}> 表示</label>
            <label><input type="radio" name="TOOL_STATUS" value="1" {{ $tool->TOOL_STATUS == 1 ? 'checked' : '' }}> 仮登録</label>
            <label><input type="radio" name="TOOL_STATUS" value="2" {{ $tool->TOOL_STATUS == 2 ? 'checked' : '' }}> マルホ確認済み</label>
            <label><input type="radio" name="TOOL_STATUS" value="3" {{ $tool->TOOL_STATUS == 3 ? 'checked' : '' }}> 中島準備完了</label>
            <label><input type="radio" name="TOOL_STATUS" value="4" {{ $tool->TOOL_STATUS == 4 ? 'checked' : '' }}> 非表示</label>
        </div>

        <div class="form-row">
            <label>ツール名</label>
            <input type="text" name="TOOL_NAME" value="{{ $tool->TOOL_NAME }}" class="text-input" required>
        </div>

        <div class="form-row">
            <label>ツール名カナ</label>
            <input type="text" name="TOOL_NAME_KANA" value="{{ $tool->TOOL_NAME_KANA }}" class="text-input" required>
        </div>

        <div class="form-row">
            <label>ツールコード</label>
            <input type="text" name="TOOL_CODE" value="{{ $tool->TOOL_CODE }}" class="text-input" required>
        </div>

        <div class="form-row">
            <label>領域</label>
            <select name="RYOIKI" class="text-input">
                @foreach($ryoikis as $code => $name)
                    <option value="{{ $code }}" {{ $tool->RYOIKI == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <label>品名</label>
            <select name="HINMEI" class="text-input">
                @foreach($hinmeis as $code => $name)
                    <option value="{{ $code }}" {{ $tool->HINMEI == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <label>ツール区分１</label>
            <select name="TOOL_TYPE1" class="text-input">
                ツール区分１
            </select>
        </div>

        <div class="form-row">
            <label>ツール区分２</label>
            <select name="TOOL_TYPE2" class="text-input">
                ツール区分２
            </select>
        </div>

        <div class="form-row">
            <label>ツール説明</label>
            <input type="text" name="TOOL_SETUMEI" value="{{ $tool->TOOL_SETUMEI }}" class="text-input">
        </div>

        <div class="form-row">
            <label>備考</label>
            <input type="text" name="REMARKS" value="{{ $tool->REMARKS }}" class="text-input">
        </div>

        <h3>PDF サムネイル情報</h3>
        <div class="form-row">
            <label>PDFファイル</label>
            <input type="file" name="TOOL_PDF_FILE" value="{{ $tool->TOOL_PDF_FILE }}" accept=".pdf" class="text-input">
        </div>
        <div class="form-row">
            <label>サムネイル画像</label>
            <input type="file" name="TOOL_THUM_FILE" value="{{ $tool->TOOL_THUM_FILE }}" accept=".jpg,.jpeg,.png" class="text-input">
        </div>

        <h3>価格情報</h3>
        <div class="form-row">
            <label>単価（円）</label>
            <input type="number" name="TANKA" value="{{ $tool->TANKA }}" class="text-input"  min="0" required>
        </div>

        <h3>表示期間</h3>
        <div class="form-row">
            <label>表示開始日</label>
            <input type="date" name="HYOJI_START_DATE" value="{{ \Carbon\Carbon::parse($tool->HYOJI_START_DATE)->format('Y/m/d') }}" class="text-input" required>
        </div>

        <div class="form-row">
            <label>表示終了日</label>
            <input type="date" name="HYOJI_END_DATE" value="{{ \Carbon\Carbon::parse($tool->HYOJI_END_DATE)->format('Y/m/d') }}" class="text-input" required>
        </div>

        <h3>管理メモ</h3>
        <div class="form-row">
            <label>管理メモ</label>
            <textarea name="MANAGEMENT_MEMO" class="text-input" rows="4">{{ $tool->MANAGEMENT_MEMO }}</textarea>
        </div>
    </div>
</form>
    {{-- 削除用フォーム --}}
    <form action="{{ route('managementtool.delete', ['id' => $tool->TOOL_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin-top: 20px;">
        @csrf
        @method('DELETE')
        <button type="hidden" name="mode" class="btn-delete" value="create">削除する</button>
    </form>
    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>


<h3 style="margin-top: 30px;">その他の情報</h3>
<div class="accordion">
    <button type="button" class="accordion-toggle">＋ 開く</button>
    <div class="accordion-content" style="display:none; margin-top: 10px;">

    <div class="content-box">
        <div class="form-row">
            <label>MSTフラグ</label>
            <input type="number" name="MST_FLG" value="{{ $tool->MST_FLG }}" class="text-input">
        </div>

        <div class="form-row">
            <label>管理期限</label>
            <input type="date" name="KANRI_LIMIT_DATE" value="{{ $tool->KANRI_LIMIT_DATE ? \Carbon\Carbon::parse($tool->KANRI_LIMIT_DATE)->format('Y-m-d') : '' }}" class="text-input">
        </div>

        <div class="form-row">
            <label>第一組織</label>
            <input type="text" name="SOSHIKI1" value="{{ $tool->SOSHIKI1 }}" class="text-input">
        </div>

        <div class="form-row">
            <label>第二組織</label>
            <input type="text" name="SOSHIKI2" value="{{ $tool->SOSHIKI2 }}" class="text-input">
        </div>

        <div class="form-row">
            <label>ツール管理者10 ID</label>
            <input type="text" name="TOOL_MANAGER10_ID" value="{{ $tool->TOOL_MANAGER10_ID }}" class="text-input">
        </div>

        <div class="form-row">
            <label>ツール管理者10 氏名</label>
            <input type="text" name="TOOL_MANAGER10_NAME" value="{{ $tool->TOOL_MANAGER10_NAME }}" class="text-input">
        </div>
    </div>
    </div>
</div>

{{-- アコーディオン動作用JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.accordion-toggle');
    const content = document.querySelector('.accordion-content');

    toggle.addEventListener('click', function () {
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggle.textContent = '− 閉じる';
        } else {
            content.style.display = 'none';
            toggle.textContent = '＋ 開く';
        }
    });
});
</script>


@endsection
