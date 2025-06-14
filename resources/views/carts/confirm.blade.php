@extends('layouts.app')

@section('content')
<div class="main">
    <h2 class="checkout-title">注文内容</h2>
    <table class="tool-detail-table mb-2rem">
        <tr>
            <th>依頼主名</th>
            <td colspan="2">{{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }} {{ $user->NAME ?? '' }}</td>
            <th>注文者</th>
            <td>{{ $user->NAME ?? '' }}</td>
        </tr>
        @php
        $postcode = $soshiki1->POST_CODE ?? '';
        $formattedPostcode = (strlen($postcode) === 7) ? substr($postcode, 0, 3) . '-' . substr($postcode, 3) : $postcode;
        @endphp
        <tr>
            <th>依頼主住所</th>
            <td>
                〒{{ $formattedPostcode }}
                {{ $soshiki1->ADDRESS1 ?? '' }} {{ $soshiki1->ADDRESS2 ?? '' }} {{ $soshiki1->ADDRESS3 ?? '' }}
                {{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}
            </td>
        </tr>
        <tr>
            <th>依頼主電話番号</th>
            <td>{{ $soshiki1->TEL ?? '' }}</td>
        </tr>
        <tr>
            <th>届け先名称</th>
            <td>{{ $delivery_name ?? '' }}</td>
        </tr>
        @php
        $postcode = $delivery_data['POST_CODE'] ?? '';
        $formattedPostcode = (strlen($postcode) === 7) ? substr($postcode, 0, 3) . '-' . substr($postcode, 3) : $postcode;
        @endphp
        <tr>
            <th>配送先住所</th>
            <td>
                〒{{ $formattedPostcode }}
                {{ $delivery_data['ADDRESS1'] ?? '' }} {{ $delivery_data['ADDRESS2'] ?? '' }} {{ $delivery_data['ADDRESS3'] ?? '' }}
                {{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}
            </td>
        </tr>
        <tr>
            <th>配送先電話番号</th>
            <td>{{ $delivery_tel }}</td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $delivery_data['NOTE'] ?? '' }}</td>
        </tr>
    </table>

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
            @foreach($cartItems as $item)
            <tr>
                <td>{{ $item['tool']->TOOL_CODE }}</td>
                <td>{{ $item['tool']->TOOL_NAME }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format($item['tool']->TANKA ?? 0) }}</td>
                <td>{{ number_format($item['subtotal']) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right">合計</td>
                <td>{{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="confirm-actions">
        <a href="{{ route('carts.checkout') }}" class="checkout-btn">戻る</a>
        <form method="POST" action="{{ route('carts.complete') }}" class="inline-form">
            @csrf
            <input type="hidden" name="EMAIL" value="{{ $delivery_data['EMAIL'] ?? '' }}">
            @foreach($cartItems as $item)
            <input type="hidden" name="cart_check[{{ $item['tool']->TOOL_CODE }}][quantity]" value="{{ $item['quantity'] }}">
            <input type="hidden" name="cart_check[{{ $item['tool']->TOOL_CODE }}][tanka]" value="{{ $item['tool']->TANKA }}">
            @endforeach
            <button type="submit" class="checkout-btn checkout-btn-main">注文確定</button>
        </form>
    </div>
</div>
@endsection