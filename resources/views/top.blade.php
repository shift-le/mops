@extends('layouts.app')

@section('content')
<h2>掲示板 新着</h2>
<a href="{{ route('board.index') }}">すべて見る</a>
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載日</th>
                <th>タイトル</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($boards as $board)
            <tr>
                <td>@if ($board->JUYOUDO_STATUS == 1)
                    <span style="color: red;">緊急</span>
                    @else
                    <span>通常</span>
                    @endif
                </td>
                <td>{{ $board->KEISAI_START_DATE }}</td>
                <td>
                    @if($board->KEIJIBAN_CATEGORY == 0)
                        <span style="display:inline-block; background:#007bff; color:#fff; padding:2px 8px; border-radius:4px; margin-left:8px;">GUIDE</span>
                    @elseif($board->KEIJIBAN_CATEGORY == 1)
                        <span style="display:inline-block; background:#28a745; color:#fff; padding:2px 8px; border-radius:4px; margin-left:8px;">INFO</span>
                    @endif
                    <a href="{{ route('board.index', ['id' => $board->KEIJIBAN_CODE]) }}">
                        {{ $board->KEIJIBAN_TITLE }}
                    </a>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>


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
