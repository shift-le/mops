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

<form method="POST" action="{{ route('managementboard.update', ['id' => $board->KEIJIBAN_CODE]) }}">
    @csrf
    @method('POST')

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
            <label><input type="radio" name="JUYOUDO_STATUS" value="0" {{ $board->JUYOUDO_STATUS == 1 ? 'checked' : '' }}>通常</label>
            <label><input type="radio" name="JUYOUDO_STATUS" value="1" {{ $board->JUYOUDO_STATUS == 0 ? 'checked' : '' }}>緊急</label>
    </div>


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

    <div class="form-row btn-row">
        <a href="{{ route('managementboard.index') }}" class="btn-clear">戻る</a>
        <button type="submit" class="submit">更新する</button>
    </div>
</form>
@endsection
