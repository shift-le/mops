<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワード再設定</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="passwordreset-wrapper">
        <div class="passwordreset-card">
            <div class="passwordreset-content">
            <div class="passwordreset-logo">
            <div class="passwordreset-sub">Maruho ondemand printing system</div>
            <div class="passwordreset-main">Mops</div>
            </div>
        <div class="passwordreset-form-area">

            @yield('content')
        </div>
    </div>
    </div>
</div>
    <script src="{{ asset('assets/users.js') }}"></script>
</body>
</html>