@extends('layouts.app')

@section('content')
<div class="user-edit-wrapper">
    <h2 class="user-edit-title">ユーザー登録情報</h2>
    <div class="user-edit-card" style="background: white; padding: 2rem; margin: 5% auto; width: 90%; max-width: 400px; text-align: center; border: 1px solid #0099FF;">
        <p class="user-edit-message" style="color: #0099FF;">パスワードを変更しました。</p>
    </div>
    <div class="user-edit-actions" style="display: flex; justify-content: center; height: 35px;">
        <a href="{{ route('top') }}" class="user-edit-btn" style="text-decoration: none; width: 130px; background-color: #0099FF; border: 1px solid #ccc; color: white; text-align: center; padding-top: 6px; font-size: small;">OK</a>
    </div>
</div>
@endsection