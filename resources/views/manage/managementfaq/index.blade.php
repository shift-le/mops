@extends('layouts.manage')

@section('content')

<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button {{ request()->routeIs('managementfaq.index') ? 'active' : '' }}">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button {{ request()->routeIs('managementfaq.create') ? 'active' : '' }}">新規</a>
    </div>
</div>

<h2>FAQ管理 一覧</h2>

<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>FAQコード</th>
                <th>タイトル</th>
                <th>質問</th>
                <th>表示/非表示</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faqs as $faq)
                <tr>
                    <td>{{ $faq->FAQ_CODE }}</td>
                    <td>{{ $faq->FAQ_TITLE }}</td>
                    <td>{{ $faq->FAQ_QUESTION }}</td>
                    <td>
                        @if ($faq->HYOJI_FLG == 1)
                            表示
                        @else
                            非表示
                        @endif
                    </td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('managementfaq.show', ['id' => $faq->FAQ_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>
                        <form action="{{ route('managementfaq.delete', ['id' => $faq->FAQ_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #dc3545; color: #fff; border: none; border-radius: 4px;">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
        {{ $faqs->links() }}
    </div>
</div>

@endsection
