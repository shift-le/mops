<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="login-wrapper" style ="background-color: #004CFFA6;">
        <div class="login-card">
            <div class="login-content">

            <div class="login-logo">
                <div class="logo-sub">Maruho ondemand printing system</div>
                <div class="logo-main">Mops<br>Manager</div>
            </div>
        <div class="login-form-area">
            @if(session('error'))
                <div class="login-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('managelogin.login') }}">
                @csrf

                <input type="text" id="USER_ID" name="USER_ID" placeholder="管理者アカウント" required>

                <input type="password" id="PASSWORD" name="password" placeholder="パスワード" required>

                <button type="submit">ログイン</button>
            </form>
            <div class="login-reset-link">
                <a href="{{ route('password.request') }}">パスワードを忘れたかたはこちら</a>
            </div>
        </div>
        </div>
        </div>
    </div>
</body>
</html>
