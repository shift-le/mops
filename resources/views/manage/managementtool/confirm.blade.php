@extends('layouts.manage')

@section('content')
<h2>ツール情報 確認</h2>

<form method="POST" action="{{ route('managementtool.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- 入力内容をhiddenで送信 --}}
    @foreach ($input as $key => $value)
        @if (is_array($value))
            @foreach ($value as $subKey => $subVal)
                <input type="hidden" name="{{ $key }}[{{ $subKey }}]" value="{{ $subVal }}">
            @endforeach
        @elseif ($key !== 'pdf_file' && $key !== 'thumbnail_image')
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <table class="tool-detail-table">
        <tr><th>ツール名</th><td>{{ $input['TOOL_NAME'] }}</td></tr>
        <tr><th>ツールコード</th><td>{{ $input['TOOL_CODE'] }}</td></tr>
        <tr><th>領域</th><td>{{ $input['RYOIKI'] }}</td></tr>
        <tr><th>品名</th><td>{{ $input['HINMEI'] }}</td></tr>
        <tr><th>価格</th><td>{{ $input['TANKA'] }} 円</td></tr>
        <tr><th>PDFファイル名</th><td>{{ $pdfFileName ?? '未選択' }}</td></tr>
        <tr><th>サムネイル画像名</th><td>{{ $thumbFileName ?? '未選択' }}</td></tr>
        {{-- 必要なだけ表示可能 --}}
    </table>

    <div class="btn-row" style="margin-top: 20px;">
        <a href="{{ url()->previous() }}" class="btn-clear">戻る</a>
        <button type="submit" class="submit">登録する</button>
    </div>
</form>
@endsection
