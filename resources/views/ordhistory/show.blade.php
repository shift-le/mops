@extends('layouts.app')

@section('content')

<h2>注文履歴 修正・キャンセル</h2>

<div class="orddetail-section">
    <h3>注文情報</h3>
    <table class="orddetail-table">
        <tr>
            <th style="text-align: center;">注文ID</th>
            <td>{{ $orderCode }}</td>
            <th style="text-align: center;">注文日</th>
            <td>{{ $orderDate }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">依頼主名</th>
            <td>{{ $header->SOSHIKI1_NAME ?? '' }} {{ $header->SOSHIKI2_NAME ?? '' }} {{ $header->IRAI_NAME ?? '' }}</td>
            <th style="text-align: center;">注文者</th>
            <td>{{ $header->ORDER_NAME ?? '' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">依頼主住所</th>
            <td colspan="3">
                {{ $header->ORDER_ADDRESS ?? '' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">依頼主電話番号</th>
            <td colspan="3">{{ $header->ORDER_PHONE ?? '―' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">届け先名称</th>
            <td colspan="4">{{ $header->DELI_NAME ?? '―' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">配送先住所</th>
            <td colspan="4">{{ $header->DELI_ADDRESS ?? '' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">配送先電話番号</th>
            <td colspan="4">{{ $header->DELI_PHONE ?? '―' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">備考</th>
            <td colspan="4" style="border: 1px solid #ccc; padding: 10px; height: 80px;">{{ $header->NOTE ?? '' }}</td>
        </tr>
    </table>
</div>

<div class="orddetail-section">
    <h3>注文明細</h3>
    <table class="tool-detail-table mb-2rem">
        <thead>
            <tr>
                <th style="text-align: center; max-width: 105px;">ツールコード</th>
                <th style="min-width: 450px;">ツール名</th>
                <th style="text-align: center; max-width: 50px;">ステータス</th>
                <th style="text-align: center; max-width: 50px;">数量</th>
                <th style="text-align: center; max-width: 50px;">単価</th>
                <th style="text-align: center; max-width: 50px;">金額</th>
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
                <td style="text-align: center;">{{ $item->ORDER_STATUS == '1' ? '印刷作業中' : '出荷済' }}</td>
                <td style="text-align: center;">{{ $quantity }}{{ $item->UNIT_NAME ?? '' }}</td>
                <td style="text-align: end;">{{ number_format($tanka) }}</td>
                <td style="text-align: end;">{{ number_format($subtotal) }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="text-right" style="text-align: center;">合計</td>
                <td colspan="5" style="text-align: end;">{{ number_format($total) }}</td>
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
                <button type="submit" class="btn btn-primary" style="width: 200px;">再発注する</button>
            </form>
        </div>
    </div>
</div>
</div>

</div>


@endsection