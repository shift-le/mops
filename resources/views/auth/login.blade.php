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
    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <div class="logo-sub">Maruho ondemand printing system</div>
                <div class="logo-main">Mops</div>
            </div>

            @if(session('error'))
                <div class="login-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <label for="USER_ID">ユーザーID</label>
                <input type="text" id="USER_ID" name="USER_ID" required>

                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">ログイン</button>
            </form>
        </div>
    </div>
</body>
</html>
