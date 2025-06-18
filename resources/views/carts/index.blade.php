@extends('layouts.app')

@section('content')

@if (session('error'))
<div class="cart-error-message">
    {{ session('error') }}
</div>
@endif
<h2 class="tool-detail-title">カートの中の確認</h2>
@if ($cartItems->isEmpty())
カートに入っているツールはありません。
@else
<p>現在、カートには以下のツールが入っています。⼀覧から外したいツールがある場合は削除を押してください。</p>
@endif


@foreach($cartItems as $item)
<div class="tool-card-cart" style="border-top: 1px solid;">
    <div class="thumbnail-cart">
        <img src="{{ asset('storage/' . $item['tool']->TOOL_THUM_FILE) }}" alt="ツール画像">
    </div>

    <div class="tool-info-cart">
        <div class="tool-name-cart">
            <div class="tool-code" style="color: black;">ツールコード：{{ $item['tool']->TOOL_CODE }}</div>
            <div class="tool-name" style="font-weight: bold;">{{ $item['tool']->TOOL_NAME }}</div>
        </div>
        <div class="tool-description" style="display: flex; justify-content: end; border-bottom: 1px solid #ccc; gap: 30px; padding: 0 50px 12px 0;">
            <form action="{{ route('cart.update') }}" method="POST" class="quantity-form">
                @csrf
                <input type="hidden" name="TOOL_CODE" value="{{ $item['tool']->TOOL_CODE }}">
                <div class="quantity-control-actions" style="height: 35px;">
                    <input type="number" name="QUANTITY" value="{{ $item['QUANTITY'] }}" style="border: 1px solid #ccc;" readonly>
                    <span style="margin: 0 10px; padding-top: 5px;">{{ $item['unit'] }}</span>
                    <button type="button" onclick="changeQuantity(this, -1)" {{ $item['QUANTITY'] <= 1 ? 'disabled' : '' }} style="background-color: white; border: 1px solid #ccc;">−</button>
                    <button type="button" onclick="changeQuantity(this, 1)" style="background-color: white; border: 1px solid #ccc;">＋</button>
                </div>
            </form>

            <form action="{{ route('cart.remove') }}" method="POST" class="delete-form">
                @csrf
                <input type="hidden" name="TOOL_CODE" value="{{ $item['tool']->TOOL_CODE }}">
                <button type="submit" class="delete-link">削除</button>
            </form>

            <div class="subtotal">小計：{{ number_format($item['subtotal']) }}円</div>
        </div>
    </div>
</div>
@endforeach

<div style="text-align: right; font-weight: bold; padding: 1.5rem 50px 0 0; font-size: 1.2rem; border-top: 1px solid;">
    合計（税抜）　{{ number_format($total) }}円
</div>
<div class="action-buttons-cart" style="justify-content: center; text-align: center; padding-top: 1.5rem;">
    <form action="{{ route('cart.cancel') }}" method="POST" style="margin-right: 1rem;">
        @csrf
        <button type="submit" name="del" style="height: 100%; width: 200px;">全てキャンセル</button>
    </form>
    <a href="{{ route('carts.checkout') }}" class="action-buttons-cart-req" style="text-decoration: none; width:170px;">依頼主届け先入力へ</a>
</div>
<script>
    function changeQuantity(button, diff) {
        const form = button.closest('form');
        const input = form.querySelector('input[name="QUANTITY"]');
        let value = parseInt(input.value, 10) || 1;
        value += diff;
        if (value < 1) value = 1;
        input.value = value;
        form.submit();
    }
</script>

@endsection