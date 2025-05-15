<!DOCTYPE html>
<html>
<head><title>掲示板詳細</title></head>
<body>
    <h1>{{ $post['title'] }}</h1>
    <p>{{ $post['body'] }}</p>

    <a href="{{ url('/board') }}">← 掲示板一覧に戻る</a>
</body>
</html>
