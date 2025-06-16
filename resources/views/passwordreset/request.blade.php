@extends('layouts.passwordreset')

@section('content')
<div class="password-reset">パスワードのリセット</div>
<div class="password-reset-text">メールアドレスをご入力ください。パスワード再設定のご案内をお送りします。</div>

@if ($errors->any())
    <div class="passwordreset-error-message" style="color: red;text-align: left;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form class="passwordreset-form" action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="ユーザアカウント" required>
    <button type="submit" class="passwordreset-btn">送信する</button>
</form>

<div class="login-back" style="text-align: left; margin-top: 15px;">
    <a href="{{ route('login') }}" style="color: black; text-decoration: none; text-align: left;">
        ログイン</a>
</div>

<div class="login-back" style="text-align: left; margin-top: 15px;">
    <a href="{{ route('managelogin.login') }}" style="color: black; text-decoration: none; text-align: right;">
        管理ログイン</a>
</div>

@endsection
