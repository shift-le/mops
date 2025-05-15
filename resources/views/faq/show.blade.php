<!DOCTYPE html>
<html>
<head><title>FAQ詳細</title></head>
<body>
    <h1>{{ $faq['title'] }}</h1>
    <p>{{ $faq['question'] }}</p>

    <a href="{{ url('/faq') }}">← FAQ一覧に戻る</a>
</body>
</html>
