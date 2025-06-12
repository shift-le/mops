@extends('layouts.passwordreset')

@section('content')
<h2>パスワード再設定</h2>
<p>新しいパスワードを入力してください。</p>

@if ($errors->any())
    <div class="passwordreset-error-message">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form class="passwordreset-form" action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    
    <input type="password" name="password" placeholder="新しいパスワード" required>
    
    <button type="submit" class="passwordreset-btn">送信する</button>
</form>
@endsection
