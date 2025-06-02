@extends('layouts.app')

@section('content')
<div class="order-complete-main">
    <h2 class="order-complete-title">注文完了</h2>
    <div class="order-complete-box">
        <p class="order-complete-msg">注文受付を完了しました。</p>
        <p class="order-complete-detail">
            注文番号は<span class="order-complete-number">{{ $orderNumber ?? '2024010851' }}</span>です。<br>
            使用金額は<span class="order-complete-amount">￥{{ number_format($total ?? 0) }}</span>です。<br>
            注文受付メールを下記へ送信しました。
        </p>
        <div class="order-complete-user">
            <span class="order-complete-username">{{ $user->NAME ?? '山田　太郎' }} 様</span>
            <span class="order-complete-email">{{ $deliveryEmail ?? 'メールアドレス未設定' }}</span>
        </div>
    </div>
    <div class="order-complete-btn-area">
        <a href="{{ route('carts.index') }}" class="order-complete-btn">OK</a>
    </div>
</div>
@endsection