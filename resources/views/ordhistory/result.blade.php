@extends('layouts.app')

@section('content')
<div class="main">
    <h2>注文履歴一覧　{{ count($groupedOrders) }}件</h2>
    <table class="ordhistory-table">
        <thead>
            <tr>
                <th>注文ID</th>
                <th>注文日</th>
                <th>ツールコード</th>
                <th>ツール名</th>
                <th>数量</th>
                <th>ステータス</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedOrders as $orderId => $group)
            @foreach ($group as $index => $row)
            <tr>
@if ($index === 0)
<td rowspan="{{ count($group) }}">
    <a href="{{ route('ordhistory.show', $orderId) }}">{{ $orderId }}</a>
</td>
<td rowspan="{{ count($group) }}">{{ \Carbon\Carbon::parse($row->CREATE_DT)->format('Y/m/d') }}</td>
@endif
                <td>{{ $row->ORDER_TOOLID }}</td>
                <td>{{ $row->ORDER_NAME }}</td>
                <td>{{ $row->AMOUNT }}冊</td>
                <td>{{ $row->ORDER_STATUS == '1' ? '出荷済' : '注文受付' }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        <div class="tool-detail-actions">
            <div class="tool-actions-left">
                <a href="{{ route('ordhistory.index') }}" class="btn btn-secondary">戻る</a>
            </div>
        </div>
    </div>
</div>
@endsection