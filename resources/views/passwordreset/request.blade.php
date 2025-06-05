@extends('layouts.passwordreset')

@section('content')

<div class="password-reset">パスワードのリセット</div>
<div class="password-reset-text">メールアドレスをご入力ください。パスワード再設定のご案内をお送りします。</div>

@if ($errors->any())
    <div class="passwordreset-error-message">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="passwordreset-form" action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="ユーザアカウント" required>
    <button type="submit" class="passwordreset-btn">送信する</button>
    
</form>
@endsection
