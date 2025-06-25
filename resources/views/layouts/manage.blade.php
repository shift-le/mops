<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/manage.css') }}">
    {{-- FontAwesome --}}
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>

    {{-- flatpickr CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body>
    <div class="sidebar">
        <h3 class="logo" style="margin: auto;">
            <span class="logo-main">Mops Manager</span>
        </h3>

        <a href="{{ route('top') }}">
            <img src="{{ asset('assets/img/icon/house_white.png') }}" alt="TOPアイコン" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;">
            TOP
        </a>

        <a href="{{ route('managementboard.index') }}">
            <img src="{{ asset('assets/img/icon/fukidashi_white.png') }}" alt="掲示板アイコン" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;">
            掲示板管理
        </a>

        <a href="{{ route('managementfaq.index') }}">　FAQ管理</a>

        <a href="#"
            style="cursor: default; pointer-events: none; color: inherit; text-decoration: none;">
            <img src="{{ asset('assets/img/icon/book_white.png') }}"
                alt="ツール情報アイコン"
                style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;">
            ツール情報</a>
        <a href="{{ route('managementtool.index') }}">　ツール情報管理</a>

        <!-- 2次フェーズで実装 -->
        <!-- <a href="#">　品名管理</a>
        <a href="#">　領域管理</a> -->

        <a href="#"
            style="cursor: default; pointer-events: none; color: inherit; text-decoration: none;">
            <img src="{{ asset('assets/img/icon/human2_white.png') }}"
                alt="ユーザアイコン"
                style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;">
            ユーザ情報</a>


        <a href="{{ route('managementuser.index') }}">　ユーザ情報管理</a>

        <!-- 2次フェーズで実装
        <a href="#">　組織１管理</a>
        <a href="#">　組織２管理</a> -->

        <a href="{{ route('managementorder.index') }}">
            <img src="{{ asset('assets/img/icon/cart_white.png') }}" alt="受注情報アイコン" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;">
            受注情報管理</a>
    </div>

    <div class="header">
        <div style="flex-grow: 1;">
            <h1 class="page-title" style="font-size: large; margin: auto;">
                @yield('page_title', 'Mops Manager')</h1>
        </div>
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

    {{-- JSの読み込み（FontAwesomeに加えてカレンダー制御） --}}
    <script src="{{ asset('assets/calendar-control.js') }}"></script>

</body>

</html>