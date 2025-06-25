@extends('layouts.manage')

@section('page_title', 'FAQ管理')

@section('content')
<style>
/* manage.css */
.pagination-wrapper svg {
    width: 20px !important;
    height: 20px !important;
}

.pagination-wrapper .flex {
    justify-content: center;
}

.pagination-wrapper .page-link {
    padding: 4px 8px;
    margin: 0 2px;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.pagination-wrapper .page-link:hover {
    background-color: #f0f0f0;
}

.pagination-wrapper .active .page-link {
    font-weight: bold;
    background-color: #007bff;
    color: #fff;
}

</style>
<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementfaq.index') }}" class="tab-button {{ request()->routeIs('managementfaq.index') ? 'active' : '' }}">一覧</a>
        <a href="{{ route('managementfaq.create') }}" class="tab-button {{ request()->routeIs('managementfaq.create') ? 'active' : '' }}">新規</a>
    </div>
</div>

<div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;background-color:#fff;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>優先度</th>
                <th>タイトル</th>
                <th>表示/非表示</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faqs as $faq)
                <tr>
                    <td>{{ $faq->DISP_ORDER }}</td>
                    <td>{{ $faq->FAQ_TITLE }}</td>
                    <td>
                        @if ($faq->HYOJI_FLG == 1)
                            表示
                        @else
                            非表示
                        @endif
                    </td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('managementfaq.show', ['id' => $faq->FAQ_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #fff; color: #007bff; border-radius: 4px; text-decoration: none;">詳細</a>
                        <form action="{{ route('managementfaq.delete', ['id' => $faq->FAQ_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #fff; color: #dc3545;  border-radius: 4px;">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
        {{ $faqs->links('components.numeric') }}
    </div>
</div>

@endsection
