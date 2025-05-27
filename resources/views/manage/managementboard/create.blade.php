@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button {{ request()->routeIs('managementboard.index') ? 'active' : '' }}">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button {{ request()->routeIs('managementboard.create') ? 'active' : '' }}">新規</a>
    </div>
</div>

<h2>掲示板 新規作成</h2>

<form method="POST" action="{{ route('managementboard.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-row">
        <label>掲載開始日</label>
        <input type="date" name="start_date" class="text-input" required>
    </div>

    <div class="form-row">
        <label>掲載終了日</label>
        <input type="date" name="end_date" class="text-input" required>
    </div>

    <div class="form-row">
        <label>重要度</label>
        <select name="priority" class="text-input" required>
            <option value="">選択してください</option>
            <option value="1">通常</option>
            <option value="2">緊急</option>
        </select>
    </div>

    <div class="form-row">
        <label>添付ファイル</label>
        <input type="file" name="attachment" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <p style="font-size: 0.9rem;">※10MBまで/1ファイル</p>
    </div>

    <div class="form-row">
        <label>タイトル</label>
        <input type="text" name="title" class="text-input" required>
    </div>

    <div class="form-row">
        <label>カテゴリー</label>
        <select name="category" class="text-input" required>
            <option value="">選択してください</option>
            <option value="0">GUIDE</option>
            <option value="1">INFO</option>
        </select>
    </div>

    <div class="form-row">
        <label>内容</label>
        <textarea name="content" class="text-input" rows="6" required></textarea>
    </div>

    <div class="form-row">
        <label>表示フラグ</label>
        <label><input type="radio" name="display_flag" value="1" checked> 表示</label>
        <label><input type="radio" name="display_flag" value="0"> 非表示</label>
    </div>

    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>
@endsection
