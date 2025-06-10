@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button">新規</a>
    </div>
</div>

<h2>掲示板 詳細・編集</h2>
<h3>内容を変更して「確認画面へ」ボタンを押下してください。</h3>

{{-- 更新・確認画面へのフォーム --}}
<form method="POST" action="{{ route('managementboard.confirm', ['id' => $board->KEIJIBAN_CODE]) }}" enctype="multipart/form-data">
    @csrf

<<<<<<< HEAD
        <div class="form-row">
            <label>掲載開始日</label>
            <input type="date" name="KEISAI_START_DATE" value="{{ \Carbon\Carbon::parse($board->KEISAI_START_DATE)->format('Y-m-d') }}" class="text-input" required>
        </div>
        
        <div class="form-row">
            <label>掲載終了日</label>
            <input type="date" name="KEISAI_END_DATE" value="{{ \Carbon\Carbon::parse($board->KEISAI_END_DATE)->format('Y-m-d') }}" class="text-input" required>
        </div>
        
        <div class="form-row">
            <label>重要度</label>
            <label><input type="radio" name="JUYOUDO_STATUS" value="0" {{ $board->JUYOUDO_STATUS == 0 ? 'checked' : '' }}> 通常</label>
            <label><input type="radio" name="JUYOUDO_STATUS" value="1" {{ $board->JUYOUDO_STATUS == 1 ? 'checked' : '' }}> 緊急</label>
        </div>
=======
    <div class="form-row">
        <label>掲載開始日</label>
        <input type="date" name="KEISAI_START_DATE" value="{{ $board->KEISAI_START_DATE }}" class="text-input" required>
    </div>
    
    <div class="form-row">
        <label>掲載終了日</label>
        <input type="date" name="KEISAI_END_DATE" value="{{ $board->KEISAI_END_DATE }}" class="text-input" required>
    </div>
    
    <div class="form-row">
        <label>重要度</label>
        <label><input type="radio" name="JUYOUDO_STATUS" value="0" {{ $board->JUYOUDO_STATUS == 0 ? 'checked' : '' }}> 通常</label>
        <label><input type="radio" name="JUYOUDO_STATUS" value="1" {{ $board->JUYOUDO_STATUS == 1 ? 'checked' : '' }}> 緊急</label>
    </div>
>>>>>>> USER_ord_history

    <div class="form-row">
        <label>タイトル</label>
        <input type="text" name="KEIJIBAN_TITLE" value="{{ $board->KEIJIBAN_TITLE }}" class="text-input" required>
    </div>

    <div class="form-row">
        <label>カテゴリ</label><br>
        <label><input type="radio" name="KEIJIBAN_CATEGORY" value="0" {{ $board->KEIJIBAN_CATEGORY == 0 ? 'checked' : '' }}> GUIDE</label>
        <label><input type="radio" name="KEIJIBAN_CATEGORY" value="1" {{ $board->KEIJIBAN_CATEGORY == 1 ? 'checked' : '' }}> INFO</label>
    </div>

    <div class="form-row">
        <label>内容</label>
        <textarea name="KEIJIBAN_TEXT" class="text-input" rows="20" required>{{ $board->KEIJIBAN_TEXT }}</textarea>
    </div>
    
    <div class="form-row">
        <label>表示フラグ</label><br>
        <label><input type="radio" name="HYOJI_FLG" value="1" {{ $board->HYOJI_FLG == 1 ? 'checked' : '' }}> 表示</label>
        <label><input type="radio" name="HYOJI_FLG" value="0" {{ $board->HYOJI_FLG == 0 ? 'checked' : '' }}> 非表示</label>
    </div>

    <div class="form-row">
        <label>添付ファイル<br>※10MBまで/1ファイル</label>
        <input type="file" name="attachment_1" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment_2" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment_3" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment_4" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
        <input type="file" name="attachment_5" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
    </div>

    <div class="form-row">
        <label>登録日時</label>
        <p>{{ $board->CREATE_DT }}</p>
    </div>

    <div class="form-row">
        <label>更新日時</label>
        <p>{{ $board->UPDATE_DT }}</p>
    </div>

    <div class="form-row">
        <label>登録者</label>
        <p>{{ $board->CREATE_USER }}</p>
    </div>

    <div class="form-row">
        <button type="hidden" name="mode" class="submit" value ="edit">確認画面へ</button>
    </div>
</form>

{{-- 削除用フォーム --}}
<form action="{{ route('managementboard.delete', ['id' => $board->KEIJIBAN_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin-top: 20px;">
    @csrf
    @method('DELETE')
    <button type="hidden" name="mode" class="btn-delete" value="create">削除する</button>
</form>

@endsection
