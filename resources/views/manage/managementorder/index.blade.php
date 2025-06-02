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
            <h3>注文日時</h3>
            <input type="date" name="CREATE_DT" value="{{ request('CREATE_DT') }}" placeholder="開始日" class="text-input">
            <input type="date" name="UPDATE_DT" value="{{ request('UPDATE_DT') }}" placeholder="終了日" class="text-input">
            <h3>注文情報</h3>
            <input type="text" name="ORDER_CODE" value="{{ request('ORDER_CODE') }}" placeholder="注文ID" class="text-input">
            <input type="text" name="TOOL_CODE" value="{{ request('TOOL_CODE') }}" placeholder="ツールコード" class="text-input">
            <input type="text" name="TOOL_NAME" value="{{ request('TOOL_NAME') }}" placeholder="ツール名" class="text-input">
            <select name="ORDER_STATUS" class="select-input">
                <option value="">注文ステータス</option>
                <option value="1" {{ request('ORDER_STATUS') == '1' ? 'selected' : '' }}>注文確認中</option>
                <option value="2" {{ request('ORDER_STATUS') == '2' ? 'selected' : '' }}>制作中</option>
                <option value="3" {{ request('ORDER_STATUS') == '3' ? 'selected' : '' }}>完了</option>
            </select>
            <h3>注文者情報</h3>
            <select name="SOSHIKI1" class="select-input">
                <option value="">組織１</option>
                <option value="1" >組織１</option>
                <option value="2" >組織１</option>
                <option value="3" >組織１</option>
            </select>

            <select name="SOSHIKI2" class="select-input">
                <option value="">組織２</option>
                <option value="1" >組織２</option>
                <option value="2" >組織２</option>
                <option value="3" >組織２</option>
            </select>

            <input type="text" name="ORDER_NAME" value="{{ request('ORDER_NAME') }}" placeholder="注文者名" class="text-input">
            <input type="text" name="USER_ID" value="{{ request('USER_ID') }}" placeholder="社員ID" class="text-input">
            <a href="{{ route('managementorder.index') }}" class="btn-clear" style="padding: 6px 12px; background: #6c757d; color: #fff; border-radius: 4px; text-decoration: none;">検索条件をクリア</a>
            <button type="submit" class="submit">検索</button>
        </div>
    </form>
</div>

<!-- 一覧テーブル -->
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>注文ID</th>
                <th>注文者名</th>
                <th>注文日時</th>
                <th>注文ステータス</th>
                <th>ツールコード</th>
                <th>ツール名</th>
                <th>数量</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}">
                            {{ $order->ORDER_CODE }}
                        </a>
                    </td>
                    <td>{{ $order->ORDER_NAME }}</td>
                    <td>{{ $order->CREATE_DT }}</td>
                    <td>
                        @if ($order->ORDER_STATUS == '0')
                            出荷済
                        @elseif ($order->ORDER_STATUS == '1')
                            印刷作業中
                        @else
                            その他
                        @endif
                    </td>
                    <td>
                        注文明細コード
                    </td>
                    <td>
                        注文明細名
                    </td>
                    <td>
                        注文明細数量
                    </td>
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>
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
