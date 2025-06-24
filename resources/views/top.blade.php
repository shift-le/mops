@extends('layouts.app')

@section('content')
<div class="section-header">
    <h2>掲示板</h2>
    <a href="{{ route('board.index') }}">すべて見る</a>
</div>

<div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載日</th>
                <th>タイトル</th>
            </tr>
        </thead>
        <tbody style="background-color:#fff;">
            @foreach ($boards as $board)
            <tr>
                <td>
                    @if ($board->JUYOUDO_STATUS == 1)
                        <span style="color: red;">緊急</span>
                    @else
                        <span>通常</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($board->KEISAI_START_DATE)->format('Y/m/d') }}</td>
                <td>
                    <a href="{{ route('board.show', ['id' => $board->KEIJIBAN_CODE]) }}">
                        @if ($board->JUYOUDO_STATUS == 1)
                            <span style="display:inline-block; background:#28a745; color:#fff; padding:2px 8px; border-radius:4px;">INFO</span>
                        @else
                            <span style="display:inline-block; background:#007bff; color:#fff; padding:2px 8px; border-radius:4px;">GUIDE</span>
                        @endif
                        <p style="margin: 0 0 0 10px;">{{ $board->KEIJIBAN_TITLE }}</p>
                        <span style="margin-left: 6px;">▷</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="section-header">
    <h2>FAQ 新着</h2>
    <a href="{{ route('faq.index') }}">すべて見る</a>
</div>

<div class="content-box">
    <table  border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tbody style="background-color:#fff;">
            @foreach ($faqs as $faq)
            <tr>
                <td>
                    <a href="{{ route('faq.show', ['id' => $faq->FAQ_CODE]) }}">
                        {{ $faq->FAQ_TITLE }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
