@extends('layouts.manage')

@section('content')

<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="#" class="tab-button active">確認</a>
    </div>
</div>

<h2>FAQ 登録内容確認</h2>
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
">この内容でよろしければ、「確認画面へ」を押してください。</p>
<div class="content-box">
    <form method="POST" action="{{ route('managementfaq.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <label>優先度</label>
            <p>{{ $input['DISP_ORDER'] }}</p>
            <input type="hidden" name="DISP_ORDER" value="{{ $input['DISP_ORDER'] }}">
        </div>

        <div class="form-row">
            <label>タイトル</label>
            <p>{{ $input['FAQ_TITLE'] }}</p>
            <input type="hidden" name="FAQ_TITLE" value="{{ $input['FAQ_TITLE'] }}">
        </div>

        <div class="form-row">
            <label>内容</label>
            <p style="white-space: pre-wrap;">{{ $input['FAQ_QUESTION'] }}</p>
            <input type="hidden" name="FAQ_QUESTION" value="{{ $input['FAQ_QUESTION'] }}">
        </div>

        <div class="form-row">
            <label>表示</label>
            <p>
                {{ $input['HYOJI_FLG'] == 1 ? '表示' : '非表示' }}
            </p>
            <input type="hidden" name="HYOJI_FLG" value="{{ $input['HYOJI_FLG'] }}">
        </div>

        <div class="form-row btn-row">
            <a href="javascript:history.back()" class="btn-clear">戻る</a>
            <button type="submit" class="submit">登録する</button>
        </div>

    </form>
</div>

@endsection
