<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Laravel アプリ</title>
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
    <div class="category-section">
        <h2>カテゴリ</h2>

        @foreach ($ryoikis as $ryoiki)
        <div class="ryoiki-block">
            <strong class="ryoiki-title">{{ $ryoiki->RYOIKI_NAME }}</strong>

            <div class="hinmei-labels">
    @foreach ($ryoiki->hinmeis as $hinmei)
        @php
            $toolCount = $hinmei->tool_count;
        @endphp

        @if ($toolCount > 0)
            <a href="{{ route('tools.search', ['hinmei' => $hinmei->HINMEI_CODE]) }}" class="hinmei-badge">
                {{ $hinmei->HINMEI_NAME }}（{{ $toolCount }}）
            </a>
        @else
            <span class="hinmei-badge empty">
                {{ $hinmei->HINMEI_NAME }}（0）
            </span>
        @endif
    @endforeach
</div>


        </div>
        @endforeach
    </div>
    @endsection
</body>

</html>