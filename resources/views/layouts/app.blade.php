<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Mops</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="layout-wrapper">
        <div class="sidebar">
            <h3 class="logo" style="margin-top: 12px;">
                <span class="logo-main">Mops</span>
                <span class="logo-sub">Maruho ondemand printing system</span>
            </h3>

            <a href="{{ route('top') }}">TOP</a>
            <span class="subtext">Mopsのトップページ</span>

            <a href="{{ route('board.index') }}">掲示板</a>
            <span class="subtext">注文に関しての注意事項など</span>

            <a href="{{ route('categorys.index') }}">カテゴリ</a>
            <span class="subtext">各資材の領域・品名カテゴリー別</span>

            <a href="{{ route('carts.index') }}">カートを見る</a>
            <span class="subtext">現在カートに入っている資材</span>

            <a href="{{ route('favorites.search') }}">お気に入り</a>
            <span class="subtext">お気に入りに登録した資材</span>

            <a href="{{ route('ordhistory.index') }}">注文履歴</a>
            <span class="subtext">過去の発注履歴から再発注できます</span>

            <a href="{{ route('faq.index') }}">FAQ</a>
            <span class="subtext">よくある質問</span>
        </div>

        <div class="content-area">
            <div class="header">
                <div class="search-bar">
                    @php
                    use App\Http\Controllers\Auth\LoginController;
                    $toolTypeOptions = LoginController::getToolTypeOptions();
                    @endphp

                    <form id="search-form" action="{{ route('tools.search') }}" method="GET" style="display: flex; align-items: center; gap: 0.5rem;">
                        {{-- ツールコード・名 --}}
                        <div class="search-wrapper">
                            <input
                                type="text"
                                name="keyword"
                                value="{{ request('keyword') }}"
                                placeholder="ツールコード・ツール名"
                                class="search-input" style="background-color: white; height: 24px;">
                            <img src="{{ asset('assets/img/icon/loupe_black.png') }}" alt="検索" class="search-icon-img">
                        </div>

                        {{-- 日付 --}}
                        <div class="date-wrapper">
                            <input
                                type="date"
                                name="mops_add_date"
                                class="search-date"
                                value="{{ request('mops_add_date') }}">
                            <img src="{{ asset('assets/img/icon/calendar_black.png') }}" alt="カレンダー" class="calendar-icon-img">
                        </div>

                        {{-- ツール区分 --}}
                        @if(isset($toolTypeOptions))
                        <select name="tool_type2_name" class="your-select-class">
                            <option value="" disabled selected hidden>ツール区分</option>
                            @foreach($toolTypeOptions as $group)
                            <option value="{{ $group['label'] }}" {{ request('tool_type2_name') == $group['label'] ? 'selected' : '' }}>
                                {{ $group['label'] }}
                            </option>
                            @endforeach
                        </select>
                        @endif

                        {{-- 表示件数 --}}
                        <select name="per_page">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10件</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50件</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100件</option>
                        </select>

                        {{-- 検索ボタン --}}
                        <button type="submit" id="search-button" disabled>検索</button>
                    </form>
                </div>

                <div class="user-icon">
                    <img src="{{ asset('assets/img/icon/human1_white.png') }}" alt="ユーザーアイコン">
                    <div class="user-dropdown" onclick="toggleUserMenu(this)">
                        <span>
                            @if(Auth::check())
                            {{ Auth::user()->USER_ID }}
                            @else
                            ゲスト
                            @endif</span>
                        <ul class="user-dropdown-menu">
<li>
    <a href="{{ route('users.edit') }}" class="user-info-link" style="font-size: 1rem; color: #007bff; padding-left: 1rem; text-decoration: none;">ユーザ登録情報</a>
</li>
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
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('search-form');
                        const button = document.getElementById('search-button');

                        const inputs = form.querySelectorAll('input[name="keyword"], input[name="mops_add_date"], select[name="tool_type2_name"]');

                        function checkFormFilled() {
                            let filled = false;
                            inputs.forEach(input => {
                                if (input.value && input.value.trim() !== '') {
                                    filled = true;
                                }
                            });
                            button.disabled = !filled;
                        }

                        checkFormFilled();

                        inputs.forEach(input => {
                            input.addEventListener('input', checkFormFilled);
                            input.addEventListener('change', checkFormFilled);
                        });
                    });

                        function toggleUserMenu(element) {
        element.classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.user-dropdown');
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
                </script>
            </div>

            <div class="main">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>