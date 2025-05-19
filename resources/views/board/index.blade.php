<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/users.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        input[readonly], textarea[readonly] {
            border: none;
            background: transparent;
            width: 100%;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="board-section" style="width: 200%; margin: 0 auto;">
    <h2>掲示板</h2>

    <table>
        <thead>
            <tr>
                <th>重要度</th>
                <th>掲載開始日</th>
                <th>投稿タイトル</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td style="text-align: center; max-width: 10px;">
                        <input type="text" value="{{ $post['JUYOUDO_STATUS'] }}" readonly>
                    </td>
                    <td style="text-align: center; max-width: 30px;">
                        <input type="text" value="{{ $post['KEISAI_START_DATE'] }}" readonly>
                    </td>
                    <td>
                        <input type="text" value="{{ $post['KEIJIBAN_CATEGORY']}} . {{ $post['KEIJIBAN_TITLE'] }}" readonly>
                    </td>
                    <td style="text-align: center; min-width: 60px;">
                        <a href="{{ url('/board/' . $post['id']) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
</body>
</html>
