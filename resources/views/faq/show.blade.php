@extends('layouts.app')

@section('content')

<h2>FAQ 詳細</h2>

<table class="tool-detail-table">
    <tr>
        <th>FAQコード</th>
        <td>{{ $faq->FAQ_CODE }}</td>
    </tr>
    <tr>
        <th>優先度</th>
        <td>{{ $faq->DISP_ORDER }}</td>
    </tr>
    <tr>
        <th>タイトル</th>
        <td colspan="3">{{ $faq->FAQ_TITLE }}</td>
    </tr>
    <tr>
        <th>内容</th>
        <td colspan="3" style="white-space: pre-wrap;">{{ $faq->FAQ_QUESTION }}</td>
    </tr>
    <tr>
        <th>表示</th>
        <td colspan="3">
            {{ $faq->HYOJI_FLG == 1 ? '表示' : '非表示' }}
        </td>
    </tr>
</table>

<div class="form-row btn-row" style="text-align:center; margin-top:20px;">
    <a href="{{ route('faq.index') }}" class="btn-clear">一覧に戻る</a>
</div>
@endsection
