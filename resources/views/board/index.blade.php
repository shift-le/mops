<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Laravel アプリ</title>
    <!-- フォント読み込み -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>

</head>
<body>
    @extends('layouts.app')

    @section('content')
    <h1>掲示板一覧</h1>
    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ url('/board/' . $post['id']) }}">
                    {{ $post['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
    @endsection
</body>
</html>
