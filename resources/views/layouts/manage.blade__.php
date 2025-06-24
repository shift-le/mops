<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/manage.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="sidebar">
        <h3 class="logo">
            <span class="logo-main">Mops Manager</span>
        </h3>

        <a href="{{ route('manage.top') }}"><i class="fas fa-home"></i>TOP</a>
        <a href="{{ route('managementboard.index') }}"><i class="fas fa-comments"></i>掲示板管理</a>
        <a href="{{ route('managementfaq.index') }}"><i class="fas fa-comments"></i>FAQ管理</a>

        {{-- ツール情報セクション --}}
        <div class="menu-section"><i class="fas fa-wrench"></i> ツール情報</div>
        <a href="{{ route('managementtool.index') }}" class="submenu">　ツール情報管理</a>
        <span class="submenu disabled">　品名管理</span>
        <span class="submenu disabled">　領域管理</span>

        {{-- ユーザ情報セクション --}}
        <div class="menu-section"><i class="fas fa-user-cog"></i> ユーザ情報</div>
        <a href="{{ route('managementuser.index') }}" class="submenu">　ユーザ情報管理</a>
        <span class="submenu disabled">　組織１管理</span>
        <span class="submenu disabled">　組織２管理</span>

        <a href="{{ route('managementorder.index') }}"><i class="fas fa-file-invoice"></i>受注情報管理</a>
    </div>


    <div class="header">
        <div class="user-icon">
            <img src="{{ asset('assets/img/icon/human1_white.png') }}" alt="ユーザーアイコン">
            <span>        
            @if(Auth::check())
                {{ Auth::user()->USER_ID }}
            @else
                ゲスト
            @endif</span>
        </div>
        <form method="POST" action="{{ route('managelogin.logout') }}">
            @csrf
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </div>

    <div class="main">
        @yield('content')
    </div>
</body>
</html>
