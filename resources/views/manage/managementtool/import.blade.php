@extends('layouts.manage')

@section('content')

<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementtool.import') }}" class="tab-button {{ request()->routeIs('managementtool.import') ? 'active' : '' }}">インポート</a>
    </div>
</div>

<h2>ツール情報一括登録・更新インポート</h2>

{{-- メッセージ表示 --}}
<p id="import-message" style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;
    color: #006400;
    background-color: #e6f4e6;
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">
    一括登録・更新するツール情報のExcelファイルを選択して、「インポートする」ボタンを選択して下さい
</p>

<div class="content-box">
    <h3>ファイルを選択してください。</h3>

    <form method="POST" action="{{ route('managementtool.importexec') }}" enctype="multipart/form-data" id="import-form">
        @csrf
        <div class="form-row">
            <label for="import_file">ツール情報EXCEL</label>
            <input type="file" id="import_file" name="import_file" class="file-input" onchange="document.getElementById('file-path').value = this.value">
            <input type="text" id="file-path" class="text-input" placeholder="選択されたファイルパスが表示されます" readonly>
        </div>

        <div class="form-row btn-row">
            <button type="button" class="submit" onclick="openConfirmModal()">インポートする</button>
        </div>
    </form>
</div>

{{-- 確認用モーダル --}}
<div id="confirm-modal" class="modal">
    <div class="modal-content">
        <p>Excelファイルをインポートします。よろしいですか？</p>
        <div class="modal-actions">
            <button type="button" onclick="closeConfirmModal()">キャンセル</button>
            <button type="button" onclick="document.getElementById('import-form').submit()">OK</button>
        </div>
    </div>
</div>

{{-- インポート結果用モーダル --}}
@if(session('success'))
<div id="result-modal" class="modal" style="display: block;">
    <div class="modal-content">
        <h4>インポート成功</h4>
        <p>{{ session('success') }}</p>
        <button onclick="closeResultModal()">閉じる</button>
    </div>
</div>
@endif

@if(session('errors'))
<script>
window.addEventListener('DOMContentLoaded', () => {
    // エラーメッセージ用のpタグの色変更
    const messageTag = document.getElementById('import-message');
    messageTag.style.border = '2px solid #B22222';
    messageTag.style.color = '#B22222';
    messageTag.style.backgroundColor = '#fbeaea';
    messageTag.innerHTML = 'エラーが発生しました。内容をご確認ください。<br><br>' +
        `{!! nl2br(e(session('errors'))) !!}`;
});
</script>
@endif

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 100px;
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal-content {
    background-color: #fff;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    border-radius: 8px;
    text-align: center;
}
.modal-actions {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}
.modal-actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.modal-actions button:first-child {
    background-color: #ccc;
}
.modal-actions button:last-child {
    background-color: #4CAF50;
    color: white;
}
</style>

<script>
function openConfirmModal() {
    document.getElementById('confirm-modal').style.display = 'block';
}
function closeConfirmModal() {
    document.getElementById('confirm-modal').style.display = 'none';
}
function closeResultModal() {
    document.getElementById('result-modal').style.display = 'none';
}
</script>

@endsection
