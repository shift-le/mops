@extends('layouts.app')

@section('content')
<div class="section-header">
    <h2>掲示板</h2>
    <a href="{{ route('managementboard.index') }}">すべて見る</a>
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
                    @if($board->KEIJIBAN_CATEGORY == 0)
                        <span class="category-label guide">GUIDE</span>
                    @elseif($board->KEIJIBAN_CATEGORY == 1)
                        <span class="category-label info">INFO</span>
                    @endif
                    <a href="{{ route('managementboard.show', ['id' => $board->KEIJIBAN_CODE]) }}">
                        @if ($board->JUYOUDO_STATUS == 1)
                            <span style="color: red;">{{ $board->KEIJIBAN_TITLE }}</span>
                        @else
                            <span>{{ $board->KEIJIBAN_TITLE }}</span>
                        @endif
                        <span style="margin-left: 6px;">▷</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div section
<h2>FAQ 新着</h2>
<a href="{{ route('faq.index') }}">すべて見る</a>
<div class="content-box">
    <table  border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tbody style="background-color:#fff;">
            @foreach ($faqs as $faq)
            <tr>
                <td>
                    <a href="{{ route('faq.index', ['id' => $faq->FAQ_CODE]) }}">
                        {{ $faq->FAQ_TITLE }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
