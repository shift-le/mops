@extends('layouts.app')

@section('content')

<h2>掲示板 詳細</h2>

<table class="tool-detail-table">
    <tr>
        <th>重要度</th>
        <td colspan="3">
            {{ $board->JUYOUDO_STATUS == 1 ? '緊急' : '通常' }}
        </td>
    </tr>
    <tr>
        <th>タイトル</th>
        <td colspan="3">{{ $board->KEIJIBAN_TITLE }}</td>
    </tr>
    <tr>
        <th>カテゴリ</th>
        <td colspan="3">
            {{ $board->KEIJIBAN_CATEGORY == 0 ? 'GUIDE' : 'INFO' }}
        </td>
    </tr>
    <tr>
        <th>内容</th>
        <td colspan="3">
            {{ $board->KEIJIBAN_TEXT }}
        </td>
    </tr>
    <tr>
        <th>表示</th>
        <td colspan="3">
            {{ $board->HYOJI_FLG == 1 ? '表示' : '非表示' }}
        </td>
    </tr>
    <tr>
        <th>添付ファイル</th>
        <td colspan="3">
            @for ($i = 1; $i <= 5; $i++)
                @php
                    $fileField = 'FILE_PATH_' . $i;
                @endphp
                @if (!empty($board->$fileField))
                    <div style="margin-bottom: 5px;">
                        <a href="{{ asset('storage/' . $board->$fileField) }}" download>
                            添付ファイル{{ $i }}
                        </a>
                    </div>
                @endif
            @endfor
        </td>
    </tr>
</table>

<div class="form-row btn-row" style="text-align:center; margin-top:20px;">
    <a href="{{ route('board.index') }}" class="btn-clear">一覧に戻る</a>
</div>
@endsection
