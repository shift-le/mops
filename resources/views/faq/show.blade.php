<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>FAQ</title>
    <!-- フォント読み込み -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <h1>{{ $faq->FAQ_TITLE }}</h1>
    <p>{{ $faq->FAQ_QUESTION }}</p>

    <a href="{{ url('/faq') }}">← FAQ一覧に戻る</a>
    @endsection
</body>
</html>
