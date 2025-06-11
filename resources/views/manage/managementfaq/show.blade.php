@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button">新規</a>
    </div>
</div>

<h2>FAQ 詳細・編集</h2>
{{-- 確認メッセージ追加 --}}
<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;       /* 濃い緑 */
    color: #006400;                  /* 文字色も濃い緑 */
    background-color: #e6f4e6;       /* 薄い緑背景 */
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">この内容でよろしければ、「更新する」を押してください。</p>
<div class="content-box">
    <form method="POST" action="{{ route('managementfaq.confirm', ['id' => $faq->FAQ_CODE]) }}">
        @csrf
        @method('PUT')

<form method="POST" action="{{ route('managementfaq.update', ['id' => $faq->FAQ_CODE]) }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <label>FAQコード</label>
        <p>{{ $faq->FAQ_CODE }}</p>
    </div>

    <div class="form-row">
        <label>優先度</label>
        <input type="number" name="DISP_ORDER" value="{{ $faq->DISP_ORDER }}" class="text-input" required>
    </div>

    <div class="form-row">
        <label>タイトル</label>
        <input type="text" name="FAQ_TITLE" value="{{ $faq->FAQ_TITLE }}" class="text-input" required>
    </div>

    <div class="form-row">
        <label>内容</label>
        <textarea name="FAQ_QUESTION" class="text-input" rows="5" required>{{ $faq->FAQ_QUESTION }}</textarea>
    </div>

    <div class="form-row">
        <label>表示</label>
            <label><input type="radio" name="HYOJI_FLG" value="0" checked> 表示</label>
            <label><input type="radio" name="HYOJI_FLG" value="1"> 非表示</label>
        </select>
    </div>

    <div class="form-row btn-row">
        <a href="{{ route('managementfaq.index') }}" class="btn-clear">戻る</a>
        <button type="submit" class="submit">更新する</button>
    </div>
</form>
@endsection
