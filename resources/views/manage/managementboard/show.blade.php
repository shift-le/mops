<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板詳細</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }
    </style>
</head>
<body>
@extends('layouts.manage')

@section('content')
    <h1>{{ $post['KEIJIBAN_TITLE'] }}</h1>
    <p><strong>重要度：</strong>{{ $post['JUYOUDO_STATUS'] }}</p>
    <p><strong>掲載開始日：</strong>{{ $post['KEISAI_START_DATE'] }}</p>

    <a href="{{ url('/board') }}">← 掲示板一覧に戻る</a>
@endsection
</body>
</html>
