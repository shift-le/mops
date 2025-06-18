@extends('layouts.app')

@section('content')
<div class="user-edit-wrapper">
    <div class="user-edit-card">
        <h2 class="user-edit-title">変更が完了しました</h2>
        <p class="user-edit-message">パスワードの変更が完了しました。</p>
        <div class="user-edit-actions">
            <a href="{{ route('top') }}" class="user-edit-btn">TOPへ戻る</a>
        </div>
    </div>
</div>
@endsection
