<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Laravel アプリ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="layout-wrapper">
        <div class="sidebar">
            <h3 class="logo">
                <span class="logo-main">Mops</span>
                <span class="logo-sub">Maruho ondemand printing system</span>
            </h3>

            <a href="#">TOP</a>
            <span class="subtext">Mopsのトップページ</span>

            <a href="{{ route('board.index') }}">掲示板</a>
            <span class="subtext">注文に関しての注意事項など</span>

            <a href="{{ route('categorys.index') }}">カテゴリ<br>
                <span class="subtext">各資材の領域・品名カテゴリー別</span>
            </a>

            <a href="{{ route('carts.index') }}">カートを見る<br>
                <span class="subtext">現在カートに入っている資材</span>

                <a href="{{ route('favorites.search') }}">お気に入り<br>
                    <span class="subtext">お気に入りに登録した資材</span>

                    <a href="{{ route('ordhistory.index') }}">注文履歴</a>
                    <span class="subtext">過去の発注履歴から再発注できます</span>

                    <a href="{{ route('faq.index') }}">FAQ</a>
                    <span class="subtext">よくある質問</span>
        </div>

        <div class="content-area">
            <div class="header">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <input type="text" placeholder="ツールコード・ツール名" class="search-input">
                        <img src="{{ asset('assets/img/icon/loupe_black.png') }}" alt="検索" class="search-icon-img">
                    </div>

                    <div class="date-wrapper">
                        <input type="date" class="search-date" value="2025-01-05">
                        <img src="{{ asset('assets/img/icon/calendar_black.png') }}" alt="カレンダー" class="calendar-icon-img">
                    </div>
                    @if(isset($toolTypeOptions))
                    <form action="{{ route('tools.search') }}" method="GET">
                        <select name="tool_type2">
                            <option value="">ツール区分</option>
                            @foreach($toolTypeOptions as $group)
                            <optgroup label="{{ $group['label'] }}">
                                @foreach($group['children'] as $item)
                                <option value="{{ $item->TOOL_TYPE2 }}">{{ $item->TOOL_TYPE2_NAME }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <select>
                        <option>10件</option>
                        <option>50件</option>
                        <option>100件</option>
                    </select>
                        <button type="submit">検索</button>
                    </form>
                    @endif
                </div>

                <div class="user-icon">
                    <img src="{{ asset('assets/img/icon/human1_white.png') }}" alt="ユーザーアイコン">

                    <div class="user-dropdown" onclick="toggleUserMenu(this)">
                        ●●●● <span class="user-caret">▼</span>

                        <ul class="user-dropdown-menu">
                            <li class="user-info-text">ユーザ登録情報</li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="logout-button">ログアウト</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <script src="{{ asset('assets/users.js') }}"></script>

                <script>
                    function toggleUserMenu(el) {
                        el.classList.toggle('active');
                    }
                </script>

            </div>

            <div class="main">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>