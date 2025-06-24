@extends('layouts.manage')

@section('content')
<h2>掲示板 確認画面</h2>
<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;       /* 濃い緑 */
    color: #006400;                  /* 文字色も濃い緑 */
    background-color: #e6f4e6;       /* 薄い緑背景 */
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">この内容でよろしければ「登録する」ボタンを押下してください。</p>

<div>
    <table class ="tool-detail-table">
        <tr><th>掲載開始日</th><td>{{ $data['KEISAI_START_DATE'] }}</td>
            <th>掲載終了日</th><td>{{ $data['KEISAI_END_DATE'] }}</td></tr>
        <tr><th>重要度</th><td colspan="3">
            @if ($data['JUYOUDO_STATUS'] == 1)
                通常
            @else
                緊急
            @endif
       </td></tr>
        <tr><th>タイトル</th><td colspan="3">{{ $data['KEIJIBAN_TITLE'] }}</td></tr>
        <tr><th>カテゴリー</th><td colspan="3">
            @if ($data['KEIJIBAN_CATEGORY'] == 1)
                INFO
            @else
                GUIDE
            @endif
        </td></tr>
        <tr><th>内容</th><td colspan="3">{!! nl2br(e($data['KEIJIBAN_TEXT'])) !!}</td></tr>
        <!-- ほかの項目も同様 -->
         <tr><th>添付ファイル</th><td colspan="3">
        @if (session()->has('attachment_paths'))
            <ul>
                @foreach (session('attachment_paths') as $path)
                    <li>{{ basename($path) }}</li>
                @endforeach
            </ul>
        @endif
        </td></tr>
        <tr><th>表示</th><td colspan="3">
                    @if($data['HYOJI_FLG'] == 1)
                        表示
                    @else
                        非表示
                    @endif
                </td></tr>
    </table>
</div>

<form method="POST" action="{{ route($data['mode'] === 'edit' ? 'managementboard.update' : 'managementboard.store', $data['id'] ?? '') }}" enctype="multipart/form-data">
    @csrf
    @if($data['mode'] === 'edit')
        @method('PUT')
    @endif

    @foreach($data as $key => $value)
        @if (is_array($value))
            {{-- 添付ファイルなど配列は hidden に含めない --}}
            @continue
        @endif
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <div class="form-row btn-row" style="position:center;">
        <button type="submit">この内容で{{ $data['mode'] === 'edit' ? '更新' : '登録' }}する</button>
        <button type="reset" onclick="history.back()">入力画面に戻る</button>
    </div>
</form>

@endsection
