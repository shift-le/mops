@extends('layouts.app')

@section('content')
<div class="section-header" style="display: flex; justify-content: space-between;">
    <h2>掲示板</h2>
    <a href="{{ route('board.index') }}" style="padding: 20px 0; text-decoration: none;">すべて見る ＞</a>
</div>

<div>
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>重要度</th>
                <th>掲載日</th>
                <th style="text-align: left;">タイトル</th>
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
                    <a href="{{ route('board.show', ['id' => $board->KEIJIBAN_CODE]) }}" style="text-decoration: none;">
                        @if ($board->JUYOUDO_STATUS == 1)
                        <span style="display:inline-block; background:#28a745; color:#fff; padding:2px 8px; border-radius:4px;">INFO</span>
                        @else
                        <span style="display:inline-block; background:#007bff; color:#fff; padding:2px 8px; border-radius:4px;">GUIDE</span>
                        @endif
                        <span style="display:inline-block;">&#x3000;</span>
                        <span style="color: black;">{{ $board->KEIJIBAN_TITLE }}</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div style="display: flex; justify-content: space-between; gap: 2rem; margin-top: 2rem;">

    <!-- FAQ一覧 -->
    <div style="flex: 1;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>FAQ</h2>
            <a href="{{ route('faq.index') }}" style="text-decoration: none;">すべて見る ＞</a>
        </div>

        <div class="content-box">
            <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tbody style="background-color:#fff;">
                    @foreach ($faqs as $faq)
                    <tr>
                        <td>
                            <a href="{{ route('faq.show', ['id' => $faq->FAQ_CODE]) }}" style="text-decoration: none; color: black;">
                                {{ $faq->FAQ_TITLE }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- お問い合わせエリア -->
    <div style="flex: 1;">
        <h2>お問い合わせ</h2>
        <div class="content-box" style="background-color: #ccc; padding: 1px 2rem 0.2rem 2rem;">
            <p style="border-bottom: 1px solid gray; padding-bottom: 10px;">FAQを確認して解決しなかった場合はお気軽にお問い合わせください。</p>

            <div style="text-align: center; margin: 1.5rem 0;">
                <div style="background: #56666d; color: #fff; padding: 12px 20px; display: inline-block; font-weight: bold; width: 300px; height: 10px; line-height: 10px;">
                    <a href="mailto:mops-info@n-kobundo.co.jp" style="color: white; text-decoration: none;">mops-info@n-kobundo.co.jp</a>
                </div>
            </div>

            <p style="font-size: 0.9rem; color: #333;">
                お問合せの内容により、返答に時間がかかる場合がございます。<br>
                土・日・休日、ゴールデンウィーク、年末年始等は、営業時間外となり、<br>
                ご回答が遅れることがございますのでご了承ください。
            </p>
        </div>
    </div>
</div>

@endsection