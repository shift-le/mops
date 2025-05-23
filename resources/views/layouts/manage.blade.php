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

        <a href="#"><i class="fas fa-home"></i>TOP</a>
        <a href="{{ route('managementboard.index') }}"><i class="fas fa-comments"></i>掲示板管理</a>
        <a href="{{ route('managementfaq.index') }}"><i class="fas fa-comments"></i>FAQ管理</a>
        <a href="#"><i class="fas fa-wrench"></i>ツール情報</a>
        <a href="#"><i class="fas fa-wrench"></i>ツール情報管理</a>
        <a href="#"><i class="fas fa-box"></i>品名管理</a>
        <a href="#"><i class="fas fa-layer-group"></i>領域管理</a>
        <a href="#"><i class="fas fa-users"></i>ユーザ情報</a>
        <a href="{{ route('managementuser.index') }}"><i class="fas fa-user-cog"></i>ユーザ情報管理</a>
        <a href="#"><i class="fas fa-building"></i>組織１管理</a>
        <a href="#"><i class="fas fa-building"></i>組織２管理</a>
        <a href="#"><i class="fas fa-file-invoice"></i>受注情報管理</a>
    </div>

    <div class="header">
        <div class="user-icon">
            <img src="{{ asset('assets/img/icon/human1_white.png') }}" alt="ユーザーアイコン">
            <span>●●●●</span>
        </div>
    </div>

    <div class="main">
        @yield('content')
    </div>
</body>
</html>
