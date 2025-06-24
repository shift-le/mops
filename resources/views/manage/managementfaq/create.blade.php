@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button active">新規</a>
    </div>
</div>

<h2>FAQ 新規登録</h2>

<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;
    color: #006400;
    background-color: #e6f4e6;
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">必要事項を記入し、「確認画面へ」を押してください。</p>

@if ($errors->any())
    <div class="error-message" style="color: red; margin: 10px 0;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('managementfaq.confirm') }}">
    @csrf

    <table class="tool-detail-table">
        {{-- FAQ_CODE欄は削除済 --}}
        <tr>
            <th>優先度</th>
            <td>
                <input type="number" name="DISP_ORDER" class="text-input" style="width:90%;" required value="{{ old('DISP_ORDER') }}">
            </td>
        </tr>
        <tr>
            <th>タイトル</th>
            <td>
                <input type="text" name="FAQ_TITLE" class="text-input" style="width:90%;" required value="{{ old('FAQ_TITLE') }}">
            </td>
        </tr>
        <tr>
            <th>内容</th>
            <td>
                <textarea name="FAQ_QUESTION" class="text-input" rows="5" style="width:90%;resize: none;" required>{{ old('FAQ_QUESTION') }}</textarea>
            </td>
        </tr>
        <tr>
            <th>表示</th>
            <td>
                <label><input type="radio" name="HYOJI_FLG" value="1" {{ old('HYOJI_FLG', '1') == '1' ? 'checked' : '' }}> 表示</label>
                <label><input type="radio" name="HYOJI_FLG" value="0" {{ old('HYOJI_FLG') == '0' ? 'checked' : '' }}> 非表示</label>
            </td>
        </tr>
    </table>

    <div class="form-row btn-row" style="text-align:center; margin-top:20px;">
        <a href="{{ route('managementfaq.index') }}" class="btn-clear">キャンセル</a>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>
@endsection
