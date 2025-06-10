@extends('layouts.passwordreset')

@section('content')
<h2>送信完了</h2>
<p class="passwordreset-confirm">パスワード再設定用のメールを送信しました。</p>

<div class="passwordreset-center">
    <a href="{{ route('login') }}">
        <button class="passwordreset-btn">ログイン</button>
    </a>
</div>
@endsection
