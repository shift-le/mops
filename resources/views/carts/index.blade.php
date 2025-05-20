@extends('layouts.app')

@section('content')
    <h2 class="tool-detail-title">カートの中の確認</h2>
    <p>現在、カートには以下のツールが入っています。⼀覧から外したいツールがある場合は削除を押してください。</p>

    @foreach($cartItems as $item)
        <div class="tool-card-cart">
            <div class="thumbnail-cart">
                <img src="{{ asset('storage/' . $item['tool']->TOOL_THUM_FILE) }}" alt="ツール画像">
            </div>
            <div class="tool-info-cart">
                <div class="tool-code">ツールコード：{{ $item['tool']->TOOL_CODE }}</div>
                <div class="tool-name">{{ $item['tool']->TOOL_NAME }}</div>

                <form action="{{ route('cart.update') }}" method="POST" class="tool-actions">
                    @csrf
                    <input type="hidden" name="tool_code" value="{{ $item['tool']->TOOL_CODE }}">
                    <div class="quantity-control-actions">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}">
                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}">−</button>
                        <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}">＋</button>
                    </div>
                </form>

                <form action="{{ route('cart.remove') }}" method="POST" style="margin-top: 0.5rem;">
                    @csrf
                    <input type="hidden" name="tool_code" value="{{ $item['tool']->TOOL_CODE }}">
                    <button type="submit" class="btn">削除</button>
                </form>

                <div style="margin-top: 0.5rem;">小計：{{ number_format($item['subtotal']) }}円</div>
            </div>
        </div>
    @endforeach

    <div class="tool-actions-cart">
        <div>合計（税抜）：<strong>{{ number_format($total) }}円</strong></div>
    </div>
    <div class="action-buttons-cart">
        <button type="submit" name="del">全てキャンセル</button>
        <a href="{{ route('checkout') }}" class="action-buttons-cart-req">依頼主届け先入力へ</a>
    </div>

@endsection
