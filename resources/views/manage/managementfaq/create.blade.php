@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button active">新規</a>
    </div>
</div>

<h2>FAQ 新規登録</h2>

<form method="POST" action="{{ route('managementfaq.store') }}">
    @csrf
    <div class="form-row">
        <label>優先度（表示順）</label>
        <input type="number" name="DISP_ORDER" class="text-input" required>
    </div>
    
    <div class="form-row">
        <label>タイトル</label>
        <input type="text" name="FAQ_TITLE" class="text-input" required>
    </div>

    <div class="form-row">
        <label>内容</label>
        <textarea name="FAQ_QUESTION" class="text-input" rows="5" required></textarea>
    </div>

    <div class="form-row">
        <label>表示</label>
        <label><input type="radio" name="HYOJI_FLG" value="1" checked> 表示</label>
        <label><input type="radio" name="HYOJI_FLG" value="0"> 非表示</label>
    </div>

    <div class="form-row btn-row">
        <a href="{{ route('managementfaq.index') }}" class="btn-clear">キャンセル</a>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>
@endsection
