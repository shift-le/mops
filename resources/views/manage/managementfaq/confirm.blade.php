@extends('layouts.manage')

@section('page_title', 'FAQ管理')

@section('content')

<style>
    .tool-detail-table th {
        width: 200px;
    }
</style>

<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="#" class="tab-button active">確認</a>
    </div>
</div>

<h2>FAQ 登録内容確認</h2>

<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;
    color: #006400;
    background-color: #e6f4e6;
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">この内容でよろしければ、「登録する」を押してください。</p>

@php
    use Illuminate\Support\Facades\DB;

    $isUpdate = isset($input['FAQ_CODE']) && DB::table('FAQ')->where('FAQ_CODE', $input['FAQ_CODE'])->exists();
@endphp

<form method="POST"
    action="{{ $isUpdate ? route('managementfaq.update', ['id' => $input['FAQ_CODE']]) : route('managementfaq.store') }}">
    @csrf
    @if($isUpdate)
        @method('PUT')
        <input type="hidden" name="FAQ_CODE" value="{{ $input['FAQ_CODE'] }}">
    @endif



    <table class="tool-detail-table">
        <tr>
            <th>優先度</th>
            <td>
                {{ $input['DISP_ORDER'] }}
                <input type="hidden" name="DISP_ORDER" value="{{ $input['DISP_ORDER'] }}">
            </td>
        </tr>
        <tr>
            <th>タイトル</th>
            <td>
                {{ $input['FAQ_TITLE'] }}
                <input type="hidden" name="FAQ_TITLE" value="{{ $input['FAQ_TITLE'] }}">
            </td>
        </tr>
        <tr>
            <th>内容</th>
            <td>
                <p style="white-space: pre-wrap; margin: 0;">{{ $input['FAQ_QUESTION'] }}</p>
                <input type="hidden" name="FAQ_QUESTION" value="{{ $input['FAQ_QUESTION'] }}">
            </td>
        </tr>
        <tr>
            <th>表示</th>
            <td>
                {{ $input['HYOJI_FLG'] == 1 ? '表示' : '非表示' }}
                <input type="hidden" name="HYOJI_FLG" value="{{ $input['HYOJI_FLG'] }}">
            </td>
        </tr>
    </table>

    <div class="form-row btn-row" style="text-align:center; margin-top:20px;">
        <a href="javascript:history.back()" class="btn-clear">戻る</a>
        <button type="submit" class="submit">登録する</button>
    </div>
</form>

@endsection
