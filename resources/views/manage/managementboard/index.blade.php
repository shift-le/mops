@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button active">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button">新規</a>
    </div>
</div>

<h2>掲示板管理</h2>

<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>重要度</th>
                <th>掲載日</th>
                <th>タイトル</th>
                <th>表示/非表示</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($posts as $post)
            <tr>
                <td>
                    @if($post->JUYOUDO_STATUS == 1)
                        緊急
                    @else
                        通常
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($post->KEISAI_START_DATE)->format('Y/m/d') }}</td>
                <td>{{ $post->KEIJIBAN_TITLE }}</td>
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
                        @method('DELETE')
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
