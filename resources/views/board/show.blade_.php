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
@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: auto; padding: 20px;">
    <h2>{{ $board->KEIJIBAN_TITLE }}</h2>

    <p><strong>重要度：</strong>
        @if($board->JUYOUDO_STATUS == 1)
            <span style="color: red;">緊急</span>
        @else
            通常
        @endif
    </p>

    <p><strong>掲載開始日：</strong>
        {{ \Carbon\Carbon::parse($board->KEISAI_START_DATE)->format('Y/m/d') }}
    </p>

    <p><strong>本文：</strong></p>
    <div style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
        {!! nl2br(e($board->KEIJIBAN_TEXT)) !!}
    </div>

    @if($board->TEMP_FILE_NAME && Storage::disk('public')->exists('keijiban/' . $board->TEMP_FILE_NAME))
        <p style="margin-top: 20px;">
            <strong>添付ファイル：</strong>
            <a href="{{ asset('storage/keijiban/' . $board->TEMP_FILE_NAME) }}" download>
                {{ $board->ORIGINAL_FILE_NAME ?? '添付ファイルをダウンロード' }}
            </a>
        </p>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('board.index') }}">← 掲示板一覧に戻る</a>
    </div>
</div>
@endsection

