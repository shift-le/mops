@extends('layouts.app')

@section('content')
<style>
    .detail-label {
        font-weight: bold;
        width: 150px;
        background-color: #f2f2f2;
        padding: 10px;
        vertical-align: top;
    }
    .detail-value {
        padding: 10px;
        background-color: #fff;
    }
    .form-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    .form-table th, .form-table td {
        border: 1px solid #ccc;
    }
    .form-footer {
        text-align: center;
        margin-top: 20px;
    }
</style>

<h2>掲示板 詳細</h2>

<table class="form-table">
    <tr>
        <th class="detail-label">掲載開始日</th>
        <td class="detail-value">{{ \Carbon\Carbon::parse($board->KEISAI_START_DATE)->format('Y/m/d') }}</td>
        <th class="detail-label">掲載終了日</th>
        <td class="detail-value">{{ \Carbon\Carbon::parse($board->KEISAI_END_DATE)->format('Y/m/d') }}</td>
    </tr>
    <tr>
        <th class="detail-label">重要度</th>
        <td class="detail-value" colspan="3">
            {{ $board->JUYOUDO_STATUS == 1 ? '緊急' : '通常' }}
        </td>
    </tr>
    <tr>
        <th class="detail-label">タイトル</th>
        <td class="detail-value" colspan="3">{{ $board->KEIJIBAN_TITLE }}</td>
    </tr>
    <tr>
        <th class="detail-label">カテゴリ</th>
        <td class="detail-value" colspan="3">
            {{ $board->KEIJIBAN_CATEGORY == 0 ? 'GUIDE' : 'INFO' }}
        </td>
    </tr>
    <tr>
        <th class="detail-label">内容</th>
        <td class="detail-value" colspan="3" style="white-space: pre-wrap;">
            {{ $board->KEIJIBAN_TEXT }}
        </td>
    </tr>
    <tr>
        <th class="detail-label">添付ファイル</th>
        <td class="detail-value" colspan="3">
            @php $hasFile = false; @endphp
            @for ($i = 1; $i <= 5; $i++)
                @php $fileField = 'FILE_PATH_' . $i; @endphp
                @if (!empty($board->$fileField))
                    @php $hasFile = true; @endphp
                    <div style="margin-bottom: 5px;">
                        <a href="{{ asset('storage/' . $board->$fileField) }}" download>
                            添付ファイル{{ $i }}
                        </a>
                    </div>
                @endif
            @endfor
            @if (!$hasFile)
                <span>なし</span>
            @endif
        </td>
    </tr>
    <tr>
        <th class="detail-label">登録日時</th>
        <td class="detail-value">{{ \Carbon\Carbon::parse($board->CREATE_DT)->format('Y/m/d H:i') }}</td>
        <th class="detail-label">更新日時</th>
        <td class="detail-value">{{ \Carbon\Carbon::parse($board->UPDATE_DT)->format('Y/m/d H:i') }}</td>
    </tr>
    <tr>
        <th class="detail-label">登録者</th>
        <td class="detail-value" colspan="3">{{ $board->CREATE_USER ?? '―' }}</td>
    </tr>
</table>

<div class="form-footer">
    <a href="{{ route('board.index') }}" class="btn btn-secondary">一覧に戻る</a>
</div>
@endsection
