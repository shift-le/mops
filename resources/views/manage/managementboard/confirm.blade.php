@extends('layouts.manage')

@section('content')
<h2>掲示板 確認画面</h2>
    
<div class="content-box">
    <table>
        <tr><th>優先度</th><td>{{ $data['JUYOUDO_STATUS'] }}</td></tr>
        <tr><th>タイトル</th><td>{{ $data['KEIJIBAN_TITLE'] }}</td></tr>
        <tr><th>内容</th><td>{!! nl2br(e($data['KEIJIBAN_TEXT'])) !!}</td></tr>
        <tr><th>表示</th><td>{{ $data['HYOJI_FLG'] }}</td></tr>
        <!-- ほかの項目も同様 -->
        @if (session()->has('attachment_paths'))
            <ul>
                @foreach (session('attachment_paths') as $path)
                    <li>{{ basename($path) }}</li>
                @endforeach
            </ul>
        @endif

    </table>
</div>

<form method="POST" action="{{ route($data['mode'] === 'edit' ? 'managementboard.update' : 'managementboard.store', $data['id'] ?? '') }}">
    @csrf
    @if($data['mode'] === 'edit')
        @method('PUT')
    @endif
    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <button type="submit">この内容で{{ $data['mode'] === 'edit' ? '更新' : '登録' }}する</button>
    <button type="button" onclick="history.back()">戻る</button>
</form>
@endsection
