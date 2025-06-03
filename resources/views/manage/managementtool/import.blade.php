@extends('layouts.manage')

@section('content')
    <div class="tab-wrapper">
        <div class="tab-container">
            <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
            <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
            <a href="{{ route('managementtool.import') }}" class="tab-button {{ request()->routeIs('managementtool.import') ? 'active' : '' }}">インポート</a>
        </div>
    </div>

    <h2>ユーザ情報一括登録・更新インポート</h2>
    <div class="content-box">
        <h3>ファイルを選択してください。</h3>

        <form method="POST" action="{{ route('managementtool.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <label for="import_file">ユーザ情報EXCEL</label>
                <input type="file" id="import_file" name="import_file" class="file-input" onchange="document.getElementById('file-path').value = this.value">
                <input type="text" id="file-path" class="text-input" placeholder="選択されたファイルパスが表示されます" readonly>
            </div>

            <div class="form-row btn-row">
                <button type="submit" class="submit">インポートする</button>
            </div>
        </form>
    </div>
@endsection
