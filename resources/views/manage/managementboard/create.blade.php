@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button {{ request()->routeIs('managementboard.index') ? 'active' : '' }}">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button {{ request()->routeIs('managementboard.create') ? 'active' : '' }}">新規</a>
    </div>
</div>

<h2>掲示板 新規作成</h2>

<form method="POST" action="{{ route('managementboard.confirm') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-row">
        <label>掲載開始日</label>
        <input type="date" name="KEISAI_START_DATE" class="text-input" required>
    </div>

    <div class="form-row">
        <label>掲載終了日</label>
        <input type="date" name="KEISAI_END_DATE" class="text-input" required>
    </div>

    <div class="form-row">
        <label>重要度</label>
        <label><input type="radio" name="JUYOUDO_STATUS" value="0" checked> 通常</label>
        <label><input type="radio" name="JUYOUDO_STATUS" value="1"> 緊急</label>
    </div>

    <div class="form-row">
        <label>タイトル</label>
        <input type="text" name="KEIJIBAN_TITLE" class="text-input" required>
    </div>

    <div class="form-row">
        <label>カテゴリー</label>
        <label><input type="radio" name="KEIJIBAN_CATEGORY" value="0" checked> GUIDE</label>
        <label><input type="radio" name="KEIJIBAN_CATEGORY" value="1"> INFO</label>
    </div>

    <div class="form-row">
        <label>内容</label>
        <textarea name="KEIJIBAN_TEXT" class="text-input" rows="6" required></textarea>
    </div>

    <div class="form-row">
        <label>添付ファイル<br>※10MBまで/1ファイル</label>
        <input type="file" name="attachment[]" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment[]" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment[]" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment[]" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment[]" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
    </div>

    <div class="form-row">
        <label>表示フラグ</label>
        <label><input type="radio" name="HYOJI_FLG" value="1" checked> 表示</label>
        <label><input type="radio" name="HYOJI_FLG" value="0"> 非表示</label>
    </div>

    <!-- mode hidden -->
    <input type="hidden" name="mode" value="create">

    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>
@endsection
