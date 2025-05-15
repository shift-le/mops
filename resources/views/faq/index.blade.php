<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
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
<div class="faq-section" style="width: 90%; margin: 0 auto;">
    <h2>FAQ一覧</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>タイトル</th>
                <th>重要度</th>
                <th>掲載日</th>
                <th>内容</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faqs as $faq)
                <tr>
                    <td>
                        <input type="text" value="{{ $faq['faq_title'] }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <input type="text" value="{{ $faq['disp_order'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <input type="text" value="{{ $faq['create_dt'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <textarea rows="2" readonly style="width: 100%; border: none; background: transparent;">{{ $faq['faq_question'] }}</textarea>
                    </td>
                    <td style="text-align: center;min-width: 60px;">
                        <a href="{{ url('/faq/' . $faq['id']) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

</body>
</html>
