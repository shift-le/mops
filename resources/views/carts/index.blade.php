@extends('layouts.app')

@section('content')
<div class="main">
    <h2>カートの中の確認</h2>
    <p>現在、カートには以下のツールが入っています。</p>

    @foreach($cartItems as $item)
        <div class="tool-card">
            <div class="thumbnail">
                <img src="{{ asset('storage/thumbnails/' . $item['tool']->TOOL_THUM_FILE) }}" alt="ツール画像">
            </div>
            <div class="tool-info">
                <div class="tool-code">ツールコード：{{ $item['tool']->TOOL_CODE }}</div>
                <div class="tool-name">{{ $item['tool']->TOOL_NAME }}</div>
                <p class="red-text">この画面ではサムネイルを押下してもPDFは表示されない。</p>

                <form action="{{ route('cart.update') }}" method="POST" class="tool-actions">
                    @csrf
                    <input type="hidden" name="tool_code" value="{{ $item['tool']->TOOL_CODE }}">
                    <div class="quantity-control-actions">
                        <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}">−</button>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}">
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

    <div class="tool-actions-right" style="margin-top: 2rem;">
        <div>合計（税抜）：<strong>{{ number_format($total) }}円</strong></div>
        <a href="{{ route('checkout') }}" class="btn-primary-actions">依頼主届け先入力へ</a>
    </div>
</div>
@endsection
