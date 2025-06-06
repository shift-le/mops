@extends('layouts.app')

@section('content')

<h2>注文履歴 修正・キャンセル</h2>

<div class="orddetail-section">
    <h3>注文情報</h3>
    <table class="orddetail-table">
        <tr>
            <th>注文ID</th>
            <td>{{ $orderCode }}</td>
            <th>注文日</th>
            <td>{{ $orderDate }}</td>
        </tr>
        <tr>
            <th>依頼主名</th>
            <td>{{ $header->SOSHIKI1_NAME ?? '' }} {{ $header->SOSHIKI2_NAME ?? '' }} {{ $header->IRAI_NAME ?? '' }}</td>
            <th>注文者</th>
            <td>{{ $header->ORDER_NAME ?? '' }}</td>
        </tr>
        <tr>
            <th>依頼主住所</th>
            <td colspan="3">
                〒{{ $header->ORDER_POST1 ?? '' }}-{{ $header->ORDER_POST2 ?? '' }}
                {{ $header->ORDER_ADDRESS ?? '' }}
            </td>
        </tr>
        <tr>
            <th>依頼主電話番号</th>
            <td colspan="3">{{ $header->ORDER_TEL ?? '―' }}</td>
        </tr>
        <tr>
            <th>届け先名称</th>
            <td>{{ $header->DELIVERY_NAME ?? '―' }}</td>
        </tr>
        <tr>
            <th>配送先住所</th>
            <td>{{ $header->DELIVERY_ADDRESS1 ?? '' }} {{ $header->DELIVERY_ADDRESS2 ?? '' }}</td>
        </tr>
        <tr>
            <th>配送先電話番号</th>
            <td>{{ $header->DELIVERY_TEL ?? '―' }}</td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $header->NOTE ?? '' }}</td>
        </tr>
    </table>
</div>

<div class="orddetail-section">
    <h3>注文明細</h3>
    <table class="tool-detail-table mb-2rem">
        <thead>
            <tr>
                <th>ツールコード</th>
                <th>ツール名</th>
                <th>数量</th>
                <th>単価</th>
                <th>金額</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($details as $item)
            @php
            $quantity = (int) $item->AMOUNT;
            $subtotal = (int) $item->SUBTOTAL;
            $tanka = $quantity > 0 ? $subtotal / $quantity : 0;
            $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $item->TOOL_CODE }}</td>
                <td>{{ $item->TOOL_NAME }}</td>
                <td>{{ $quantity }}</td>
                <td>{{ number_format($tanka) }}</td>
                <td>{{ number_format($subtotal) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right">合計</td>
                <td>{{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    <div class="tool-detail-actions">
        <div class="tool-actions-left">
            <a href="{{ route('ordhistory.result') }}" class="btn btn-secondary">戻る</a>
            </div>
            <div class="tool-actions-right">
            <form method="POST" action="{{ route('ordhistory.repeat', $orderCode) }}">
                @csrf
                <button type="submit" class="btn btn-primary">再発注する</button>
            </form>
            </div>
        </div>
    </div>
</div>

</div>


@endsection