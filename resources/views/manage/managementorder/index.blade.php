@extends('layouts.manage')

@section('content')

<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementorder.index') }}" class="tab-button {{ request()->routeIs('managementorder.index') ? 'active' : '' }}">検索・一覧</a>
    </div>
</div>

<h2>受注情報 一覧</h2>

<!-- 検索フォーム -->
<div class="search-box">
    <form method="GET" action="{{ route('managementorder.index') }}">
        <div class="form-row">
            <input type="text" name="order_code" value="{{ request('order_code') }}" placeholder="注文コード" class="text-input">
            <input type="text" name="irai_name" value="{{ request('irai_name') }}" placeholder="注文者名" class="text-input">
            <select name="order_status" class="select-input">
                <option value="">ステータス選択</option>
                <option value="1" {{ request('order_status') == '1' ? 'selected' : '' }}>注文確認中</option>
                <option value="2" {{ request('order_status') == '2' ? 'selected' : '' }}>制作中</option>
                <option value="3" {{ request('order_status') == '3' ? 'selected' : '' }}>完了</option>
            </select>

            <button type="submit" class="submit">検索</button>
            <a href="{{ route('managementorder.index') }}" class="btn-clear" style="padding: 6px 12px; background: #6c757d; color: #fff; border-radius: 4px; text-decoration: none;">クリア</a>
        </div>
    </form>
</div>

<!-- 一覧テーブル -->
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>注文コード</th>
                <th>ツール名</th>
                <th>注文者名</th>
                <th>数量</th>
                <th>注文日時</th>
                <th>注文ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->ORDER_CODE }}</td>
                    <td>{{ $order->ORDER_NAME }}</td>
                    <td>{{ $order->IRAI_NAME }}</td>
                    <td>{{ $order->AMOUNT }}</td>
                    <td>{{ $order->CREATE_DT }}</td>
                    <td>
                        @if ($order->ORDER_STATUS == '1')
                            注文確認中
                        @elseif ($order->ORDER_STATUS == '2')
                            制作中
                        @else
                            その他
                        @endif
                    </td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>
                        <form action="{{ route('managementorder.delete', ['id' => $order->ORDER_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
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
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

@endsection
