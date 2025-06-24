<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user.css') }}">
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
<div class="board-section" style="width: 100%; margin: 0 auto;">
    <h2>掲示板</h2>

    <table>
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載開始日</th>
                <th>投稿タイトル</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody style="background-color:#fff;">
            @foreach ($posts as $post)
                <tr>
                    <td style="text-align: center; max-width: 10px;">
                        <input type="text" value="{{ $post->JUYOUDO_STATUS }}" readonly>
                    </td>
                    <td style="text-align: center; max-width: 30px;">
                        <input type="text" value="{{ $post->KEISAI_START_DATE }}" readonly>
                    </td>
                    <td>                    
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            @if($post->KEIJIBAN_CATEGORY == 0)
                                <span style="display:inline-block; background:#007bff; color:#fff; padding:2px 8px; border-radius:4px;">GUIDE</span>
                            @elseif($post->KEIJIBAN_CATEGORY == 1)
                                <span style="display:inline-block; background:#28a745; color:#fff; padding:2px 8px; border-radius:4px;">INFO</span>
                            @endif
                            <p style="margin: 0 0 0 10px;">{{ $post->KEIJIBAN_TITLE }}</p>
                        </div>
                    </td>
                    <td style="text-align: center; min-width: 60px;">
                        <a href="{{ url('/board/' . $post->KEIJIBAN_CODE) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
</body>
</html>
