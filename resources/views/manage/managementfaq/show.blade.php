@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button">新規</a>
    </div>
</div>

<h2>FAQ 詳細・編集</h2>

<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;
    color: #006400;
    background-color: #e6f4e6;
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">この内容でよろしければ、「更新する」を押してください。</p>

<form method="POST" action="{{ route('managementfaq.confirm', ['id' => $faq->FAQ_CODE]) }}">
    @csrf

    <table class="tool-detail-table">
        <tr>
            <th>FAQコード</th>
            <td>{{ $faq->FAQ_CODE }}</td>
        </tr>
        <tr>
            <th>優先度</th>
            <td>
                <input type="number" name="DISP_ORDER" value="{{ $faq->DISP_ORDER }}" class="text-input" style="width:90%;" required>
            </td>
        </tr>
        <tr>
            <th>タイトル</th>
            <td colspan="3">
                <input type="text" name="FAQ_TITLE" value="{{ $faq->FAQ_TITLE }}" class="text-input" style="width:90%;" required>
            </td>
        </tr>
        <tr>
            <th>内容</th>
            <td colspan="3">
                <textarea name="FAQ_QUESTION" class="text-input" rows="5" style="width:90%;resize: none;" required>{{ $faq->FAQ_QUESTION }}</textarea>
            </td>
        </tr>
        <tr>
            <th>表示</th>
            <td colspan="3">
                <label><input type="radio" name="HYOJI_FLG" value="0" {{ $faq->HYOJI_FLG == 0 ? 'checked' : '' }}> 表示</label>
                <label><input type="radio" name="HYOJI_FLG" value="1" {{ $faq->HYOJI_FLG == 1 ? 'checked' : '' }}> 非表示</label>
            </td>
        </tr>
    </table>

    <table class="tool-detail-table">
        <tr>
            <th>登録日時</th>
            <td>{{ $faq->CREATE_DT}}</td>
            <th>更新日時</th>
            <td>{{ $faq->UPDATE_DT}}</td>
        </tr>
        <tr>
            <th>登録者</th>
            <td>{{ $faq->CREATE_USER}}<td>
        </tr>
    </table>

    <div class="form-row btn-row" style="text-align:center; margin-top:20px;">
        <a href="{{ route('managementfaq.index') }}" class="btn-clear">戻る</a>
        <button type="submit" class="submit">更新する</button>
    </div>
</form>
@endsection
