@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button active">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button">新規</a>
    </div>
</div>

<div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載日</th>
                <th width="700px">タイトル</th>
                <th>表示/非表示</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="background-color:#fff">
        @foreach ($posts as $post)
            <tr>
                <td>
                    @if($post->JUYOUDO_STATUS == 1)
                        <span style="color:#ff0000;">緊急</span>
                    @else
                        通常
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($post->KEISAI_START_DATE)->format('Y/m/d') }}</td>
                <td>
                    @if($post->KEIJIBAN_CATEGORY == 0)
                        <span style="display:inline-block; background:#007bff; color:#fff; padding:2px 8px; border-radius:4px; margin-left:8px;">GUIDE</span>
                    @elseif($post->KEIJIBAN_CATEGORY == 1)
                        <span style="display:inline-block; background:#28a745; color:#fff; padding:2px 8px; border-radius:4px; margin-left:8px;">INFO</span>
                    @endif
                    <span style="display:inline-block;">&#x3000;</span>
                    @if($post->JUYOUDO_STATUS == 1)
                        <span style="color:#ff0000;">{{ $post->KEIJIBAN_TITLE }}</span>
                    @else
                        {{ $post->KEIJIBAN_TITLE }}
                    @endif
                </td>
                <td>
                    @if($post->HYOJI_FLG == 1)
                        表示
                    @else
                        非表示
                    @endif
                </td>
                <td style="display:flex; gap:8px;">
                    <a href="{{ route('managementboard.show', ['id' => $post->KEIJIBAN_CODE]) }}" class="btn-detail">詳細</a>
                    <form action="{{ route('managementboard.delete', ['id' => $post->KEIJIBAN_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        <button type="submit" class="btn-delete">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
        {{ $posts->links() }}
    </div>
</div>
@endsection
