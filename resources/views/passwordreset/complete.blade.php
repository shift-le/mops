@extends('layouts.passwordreset')

@section('content')
<h2>パスワード変更完了</h2>
<p class="passwordreset-confirm">パスワードの変更が完了しました。</p>

<div class="passwordreset-center">
    <a href="{{ route('login') }}">
        <button class="passwordreset-btn">ログイン</button>
    </a>
</div>
@endsection
