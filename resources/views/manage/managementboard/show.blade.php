@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button">新規</a>
    </div>
</div>

<h2>掲示板 詳細・編集</h2>


<form method="POST" action="{{ route('managementboard.confirm', ['id' => $board->KEIJIBAN_CODE]) }}" enctype="multipart/form-data">
    @csrf

    <table class="tool-detail-table">
        <tr>
            <th>掲載開始日</th>
            <td><input type="date" name="KEISAI_START_DATE" value="{{ \Carbon\Carbon::parse($board->KEISAI_START_DATE)->format('Y-m-d') }}" class="text-input" style="width:90%;" required></td>
            <th>掲載終了日</th>
            <td><input type="date" name="KEISAI_END_DATE" value="{{ \Carbon\Carbon::parse($board->KEISAI_END_DATE)->format('Y-m-d') }}" class="text-input" style="width:90%;" required></td>
        </tr>
        <tr>
            <th>重要度</th>
            <td colspan="3">
                <label><input type="radio" name="JUYOUDO_STATUS" value="0" {{ $board->JUYOUDO_STATUS == 0 ? 'checked' : '' }}> 通常</label>
                <label><input type="radio" name="JUYOUDO_STATUS" value="1" {{ $board->JUYOUDO_STATUS == 1 ? 'checked' : '' }}> 緊急</label>
            </td>
        </tr>
        <tr>
            <th>タイトル</th>
            <td colspan="3"><input type="text" name="KEIJIBAN_TITLE" value="{{ $board->KEIJIBAN_TITLE }}" class="text-input" style="width:90%;"required></td>
        </tr>
        <tr>
            <th>カテゴリ</th>
            <td colspan="3">
                <label><input type="radio" name="KEIJIBAN_CATEGORY" value="0" {{ $board->KEIJIBAN_CATEGORY == 0 ? 'checked' : '' }}> GUIDE</label>
                <label><input type="radio" name="KEIJIBAN_CATEGORY" value="1" {{ $board->KEIJIBAN_CATEGORY == 1 ? 'checked' : '' }}> INFO</label>
            </td>
        </tr>
        <tr>
            <th>内容</th>
            <td colspan="3">
                <textarea name="KEIJIBAN_TEXT" class="text-input" rows="10" style="width:90%;resize: none;" required>{{ $board->KEIJIBAN_TEXT }}</textarea>
            </td>
        </tr>
        <tr>
            <th>表示フラグ</th>
            <td colspan="3">
                <label><input type="radio" name="HYOJI_FLG" value="1" {{ $board->HYOJI_FLG == 1 ? 'checked' : '' }}> 表示</label>
                <label><input type="radio" name="HYOJI_FLG" value="0" {{ $board->HYOJI_FLG == 0 ? 'checked' : '' }}> 非表示</label>
            </td>
        </tr>
        <tr>
            <th>添付ファイル<br>※10MBまで/1ファイル</th>
            <td colspan="3">
                <input type="file" name="attachment_1" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input"><br>
                <input type="file" name="attachment_2" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input"><br>
                <input type="file" name="attachment_3" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input"><br>
                <input type="file" name="attachment_4" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input"><br>
                <input type="file" name="attachment_5" accept=".jpg,.png,.pdf,.doc,.docx" class="text-input">
            </td>
        </tr>
    </table>

    <table class="tool-detail-table">
        <tr>
            <th>登録日時</th>
            <td>{{ $board->CREATE_DT }}</td>
            <th>更新日時</th>
            <td>{{ $board->UPDATE_DT }}</td>
        </tr>
        <tr>
            <th>登録者</th>
            <td colspan="3">{{ $board->CREATE_USER }}</td>
        </tr>
    </table>

    <div class="form-row btn-row" style="text-align:center; margin-top:20px;">
        <input type="hidden" name="mode" value="edit">
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>

<form action="{{ route('managementboard.delete', ['id' => $board->KEIJIBAN_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin-top: 20px; text-align:center;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-delete">削除する</button>
</form>

@endsection
