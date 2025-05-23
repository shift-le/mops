<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
</head>
<body>
    <h3>Maruho ondemand printing system</h3>
    <h1>Mops<br>Manager</h1>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/manage/login">
        @csrf
        <div>
            <label for="login_id">ログインID:</label>
            <input type="text" id="login_id" name="login_id">
        </div>
        <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit">ログイン</button>
    </form>
</body>
</html>
