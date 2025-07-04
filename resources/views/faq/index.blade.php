<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FAQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }

        .faq-link {
            float: right;
            text-decoration: none;
            font-size: 16px;
            color: #007BFF;
            margin-left: 10px;
        }

        .faq-link:hover {
            text-decoration: underline;
        }

        textarea {
            resize: none;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="faq-section" style="width: 100%; margin: 0 auto;">
    <h2>FAQ</h2>

        <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載日</th>
                <th>タイトル</th>
            </tr>
        </thead>
        <tbody style="background-color:#fff;">
            @foreach ($faqs as $faq)
                <tr>
                    <td>
                        <input type="text" value="{{ $faq->DISP_ORDER ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <input type="text" value="{{ $faq->CREATE_DT ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                    </td>
                    <td>
                        <input type="text" value="{{ $faq->FAQ_TITLE }}" readonly style="width: 100%; border: none; background: transparent;">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <a class="faq-link" href="{{ url('/faq/' . $faq->FAQ_CODE) }}" style="text-decoration: none; font-size: 18px;">▷</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
</body>
</html>
