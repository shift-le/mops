@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementtool.import') }}" class="tab-button">インポート</a>
    </div>
</div>

<h2>ユーザ情報 新規登録 入力</h2>

<form method="POST" action="{{ route('managementtool.store') }}">
    @csrf

    <div class="content-box">
        <h3>基本情報</h3>

        <div class="form-row">
            <label>ステータス</label>
            <label><input type="radio" name="TOOL_STATUS" value="0" checked> 表示</label>
            <label><input type="radio" name="TOOL_STATUS" value="1"> 仮登録</label>
            <label><input type="radio" name="TOOL_STATUS" value="2"> マルホ確認済み</label>
            <label><input type="radio" name="TOOL_STATUS" value="3"> 中島準備完了</label>
            <label><input type="radio" name="TOOL_STATUS" value="4"> 非表示</label>
        </div>

        <div class="form-row">
            <label>ツール名</label>
            <input type="text" name="TOOL_NAME" class="text-input" required>
        </div>

        <div class="form-row">
            <label>ツール名カナ</label>
            <input type="text" name="TOOL_NAME_KANA" class="text-input" required>
        </div>

        <div class="form-row">
            <label>ツールコード</label>
            <input type="text" name="TOOL_CODE" class="text-input" required>
        </div>

        <div class="form-row">
            <label>領域</label>
            <select name="RYOIKI" class="text-input">
                <option value="">選択</option>
                <option value="R001">領域1</option>
                <option value="R002">領域2</option>
            </select>
        </div>

        <div class="form-row">
            <label>品名</label>
            <select name="HINMEI" class="text-input">
                <option value="">選択</option>
                <option value="H001">品名1</option>
                <option value="H002">品名2</option>
            </select>
        </div>
    
        <div class="form-row">
            <label>ツール区分１</label>
            <select name="TOOL_TYPE1" class="text-input">
                <option value="">選択</option>
                <option value="">ツール区分１</option>
                <option value="">ツール区分１</option>
            </select>
        </div>

        <div class="form-row">
            <label>ツール区分２</label>
            <select name="HINMEI" class="text-input">
                <option value="">選択</option>
                <option value="">ツール区分２</option>
                <option value="">ツール区分２</option>
            </select>
        </div>        

        <div class="form-row">
            <label>ツール説明</label>
            <input type="text" name="TOOL_SETUMEI" class="text-input">
        </div>

        <div class="form-row">
            <label>備考</label>
            <input type="text" name="REMARKS" class="text-input">
        </div>

    <h3>PDF サムネイル情報</h3>
        <div class="form-row">
            <label>PDFファイル</label>
            <input type="file" name="pdf_file" accept=".pdf" class="text-input">
        </div>
        <div class="form-row">
            <label>サムネイル画像</label>
            <input type="file" name="thumbnail_image" accept=".jpg,.jpeg,.png" class="text-input">
        </div>

    <h3>価格情報</h3>
        <div class="form-row">
            <label>単価（円）</label>
            <input type="number" name="TANKA" class="text-input" step="0.01" min="0" required>
        </div>

    <h3>表示機関</h3>
        <div class="form-row">
            <label>表示開始日</label>
            <input type="date" name="HYOJI_START_DATE" class="text-input" required>
        </div>

        <div class="form-row">
            <label>表示終了日</label>
            <input type="date" name="HYOJI_END_DATE" class="text-input" required>
        </div>

    <h3>管理メモ</h3>
        <div class="form-row">
            <label>管理メモ</label>
            <textarea name="MANAGEMENT_MEMO" class="text-input" rows="4"></textarea>
        </div>
        
    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>

<script>
document.getElementById('resident_check').addEventListener('change', function () {
    document.getElementById('resident_fields').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
