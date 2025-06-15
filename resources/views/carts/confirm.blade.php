@extends('layouts.app')

@section('content')
<div class="main">
    <h2 class="checkout-title">注文内容</h2>

    <table class="tool-detail-table mb-2rem" style="word-break: break-word; white-space: normal;">
        <tr>
            <th style="text-align: center;">依頼主名</th>
            <td style="max-width: 200px;">{{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }} {{ $user->NAME ?? '' }}</td>
            <th style="text-align: center;">注文者</th>
            <td>{{ $user->NAME ?? '' }}</td>
        </tr>
        @php
        $postcode = $soshiki1->POST_CODE ?? '';
        $formattedPostcode = (strlen($postcode) === 7) ? substr($postcode, 0, 3) . '-' . substr($postcode, 3) : $postcode;
        @endphp
        <tr>
            <th style="text-align: center;">依頼主住所</th>
            <td colspan="3">
                〒{{ $formattedPostcode }}
                {{ $soshiki1->ADDRESS1 ?? '' }} {{ $soshiki1->ADDRESS2 ?? '' }} {{ $soshiki1->ADDRESS3 ?? '' }}
                {{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}
            </td>
        </tr>
        <tr>
            <th style="text-align: center;">依頼主電話番号</th>
            <td colspan="3">
                {{ $soshiki1->TEL ?? '' }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">届け先名称</th>
            <td colspan="3">
                {{ $delivery_name ?? '' }}</td>
        </tr>
        @php
        $postcode = $delivery_data['POST_CODE'] ?? '';
        $formattedPostcode = (strlen($postcode) === 7) ? substr($postcode, 0, 3) . '-' . substr($postcode, 3) : $postcode;
        @endphp
        <tr>
            <th style="text-align: center;">配送先住所</th>
            <td colspan="3">
                〒{{ $formattedPostcode }}
                {{ $delivery_data['ADDRESS1'] ?? '' }} {{ $delivery_data['ADDRESS2'] ?? '' }} {{ $delivery_data['ADDRESS3'] ?? '' }}
                {{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}
            </td>
        </tr>
        <tr>
            <th style="text-align: center;">配送先電話番号</th>
            <td colspan="3">
                {{ $delivery_tel }}</td>
        </tr>
        <tr>
            <th style="text-align: center;">備考</th>
            <td colspan="3" style="max-width: 1000px;">
                {{ $delivery_data['NOTE'] ?? '' }}</td>
        </tr>
    </table>

    <table class="tool-detail-table mb-2rem" style="word-break: break-word; white-space: normal;">
        <thead>
            <tr>
                <th style="text-align: center; max-width: 164px;">ツールコード</th>
                <th style="width: auto;">ツール名</th>
                <th style="text-align: center; max-width: 100px;">数量</th>
                <th style="text-align: center; max-width: 100px;">単価</th>
                <th style="text-align: center; max-width: 100px;">金額</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
            <tr>
                <td>{{ $item['tool']->TOOL_CODE }}</td>
                <td>{{ $item['tool']->TOOL_NAME }}</td>
                <td style="text-align: center;">{{ $item['QUANTITY'] }}{{ $item['unit'] }}</td>
                <td style="text-align: end;">{{ number_format($item['tool']->TANKA ?? 0) }}</td>
                <td style="text-align: end;">{{ number_format($item['subtotal']) }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="text-right" style="text-align: center;">合計</td>
                <td colspan="4" style="text-align: end;">{{ number_format($total) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="confirm-actions" style="display: flex;">
        <a href="{{ route('carts.checkout') }}" class="checkout-btn" style="background-color: black; width: 100px; border-radius: 1px; text-decoration-line: none;">
            戻る</a>

        <form method="POST" action="{{ route('carts.complete') }}" class="inline-form" id="confirmForm" style="margin: auto;">
            @csrf
            <input type="hidden" name="EMAIL" value="{{ $delivery_data['EMAIL'] ?? '' }}">
            <input type="hidden" name="delivery_name" value="{{ $delivery_name ?? '' }}">
            @foreach($cartItems as $item)
            <input type="hidden" name="cart_check[{{ $item['tool']->TOOL_CODE }}][QUANTITY]" value="{{ $item['QUANTITY'] }}">
            <input type="hidden" name="cart_check[{{ $item['tool']->TOOL_CODE }}][tanka]" value="{{ $item['tool']->TANKA }}">
            @endforeach
            <div class="ord_confirm">
            <button type="button" id="openModal" class="checkout-btn checkout-btn-main" style="width: 210px; background: #007bff; border-radius: 1px;">
                注文確定</button>
            </div>
        </form>
    </div>
</div>

<!-- モーダル -->
<div id="confirmModal" class="modal" style="display:none; position:fixed; z-index:1000; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <div style="background:#fff; padding:2rem; margin:10% auto; width:90%; max-width:400px; border-radius:8px; text-align:center;">
        <p>注文を確定しますか？</p>
        <div style="margin-top: 1.5rem;">
            <button id="modalCancel" class="checkout-btn">キャンセル</button>
            <button id="modalConfirm" class="checkout-btn checkout-btn-main">確定する</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('openModal').addEventListener('click', function () {
        document.getElementById('confirmModal').style.display = 'block';
    });

    document.getElementById('modalCancel').addEventListener('click', function () {
        document.getElementById('confirmModal').style.display = 'none';
    });

    document.getElementById('modalConfirm').addEventListener('click', function () {
        document.getElementById('confirmForm').submit();
    });
</script>
@endsection
