@extends('layouts.manage')

@section('content')

{{-- 掲示板一覧 --}}
<div class="board-section" style="width: 100%; margin-bottom: 50px;">
    <h2>掲示板一覧</h2>

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
            <!-- @foreach ($boards as $post) -->
                <tr>
                    <td style="text-align: center; max-width: 10px;">
                        <!-- <input type="text" value="{{ $post->JUYOUDO_STATUS }}" readonly> -->
                         例１
                    </td>
                    <td style="text-align: center; max-width: 30px;">
                        <!-- <input type="text" value="{{ $post->KEISAI_START_DATE }}" readonly> -->
                         例２
                    </td>
                    <td>
                        <!-- <input type="text" value="{{ $post->KEIJIBAN_CATEGORY }} . {{ $post->KEIJIBAN_TITLE }}" readonly> -->
                         例３
                    </td>
                    <td style="text-align: center; min-width: 60px;">
                        <!-- <a href="{{ url('/board/' . $post->id) }}">詳細</a> -->
                         詳細
                    </td>
                </tr>
            <!-- @endforeach -->
        </tbody>
    </table>
</div>

{{-- FAQ一覧 --}}
<div class="faq-section" style="width: 100%; margin-bottom: 50px;">
    <h2>FAQ 一覧</h2>

    <table>
        <thead>
            <tr>
                <th>カテゴリ</th>
                <th>質問内容</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            <!-- @foreach ($faqs as $faq) -->
                <tr>
                    <td style="text-align: center; max-width: 150px;">
                        <!-- <input type="text" value="{{ $faq->category }}" readonly> -->
                         例１
                    </td>
                    <td>
                        <!-- <input type="text" value="{{ $faq->question }}" readonly> -->
                         例２
                    </td>
                    <td style="text-align: center; min-width: 60px;">
                        <!-- <a href="{{ url('/faq/' . $faq->id) }}">詳細</a> -->
                         例３
                    </td>
                </tr>
            <!-- @endforeach -->
        </tbody>
    </table>
</div>

@endsection
