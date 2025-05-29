@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementuser.index') }}" class="tab-button {{ request()->routeIs('managementuser.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementuser.create') }}" class="tab-button {{ request()->routeIs('managementuser.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementuser.import') }}" class="tab-button {{ request()->routeIs('managementuser.import') ? 'active' : '' }}">インポート</a>
    </div>
</div>

<h2>ユーザ情報 詳細・編集</h2>
<h3><br>基本情報</h3>

<div class="content-box">
    <form method="POST" action="{{ route('managementuser.update', ['id' => $user->USER_ID]) }}">
        @csrf
        <div class="form-row">
            <label>社員ID</label>
            <input type="text" name="USER_ID" class="text-input" value="{{ $user->USER_ID }}" readonly>
        </div>

        <div class="form-row">
            <label>氏名</label>
            <input type="text" name="NAME" class="text-input" value="{{ $user->NAME }}" required>
        </div>

        <div class="form-row">
            <label>氏名カナ</label>
            <input type="text" name="NAME_KANA" class="text-input" value="{{ $user->NAME_KANA }}" required>
        </div>

        <div class="form-row">
            <label>メールアドレス</label>
            <input type="email" name="EMAIL" class="text-input" value="{{ $user->EMAIL }}" required>
        </div>

        <div class="form-row">
            <label>パスワード</label>
            <input type="text" name="PASSWORD" class="text-input" value="{{ $user->PASSWORD }}" required>
        </div>

        <div class="form-row">
            <label>携帯電話番号</label>
            <input type="text" name="MOBILE_TEL" class="text-input" value="{{ $user->MOBILE_TEL }}">
        </div>

        <div class="form-row">
            <label>携帯メールアドレス</label>
            <input type="text" name="MOBILE_EMAIL" class="text-input" value="{{ $user->MOBILE_EMAIL }}">
        </div>

        <div class="form-row">
            <label>支店・部</label>
            <input type="text" name="SHITEN_BU_CODE" class="text-input" value="{{ $user->SHITEN_BU_CODE }}">
        </div>

        <div class="form-row">
            <label>営業所・グループ</label>
            <input type="text" name="EIGYOSHO_GROUP_CODE" class="text-input" value="{{ $user->EIGYOSHO_GROUP_CODE }}">
        </div>

        <div class="form-row btn-row">
            <button type="reset" class="btn-clear">キャンセル</button>
            <button type="submit" class="submit">更新する</button>
        </div>
    </form>
</div>
@endsection
