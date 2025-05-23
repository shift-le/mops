@extends('layouts.manage')

@section('content')
    <!-- タブボタンボックス -->
    <div class="tab-wrapper">
        <div class="tab-container">
            <a href="{{ route('managementuser.index') }}" class="tab-button {{ request()->routeIs('managementuser.index') ? 'active' : '' }}">検索・一覧</a>
            <a href="{{ route('managementuser.create') }}" class="tab-button {{ request()->routeIs('managementuser.create') ? 'active' : '' }}">新規</a>
            <a href="{{ route('managementuser.import') }}" class="tab-button {{ request()->routeIs('managementuser.import') ? 'active' : '' }}">インポート</a>
            <a href="{{ route('managementuser.export') }}" class="tab-button {{ request()->routeIs('managementuser.export') ? 'active' : '' }}">エクスポート</a>
        </div>
    </div>

    <h2>ユーザ情報一括エクスポート</h2>

    <div class="content-box">
        <h3>EXCELファイルを出力します。</h3>

        <form method="POST" action="{{ route('managementuser.export.exec') }}">
            @csrf
            <button type="submit" class="submit">エクスポートする</button>
        </form>

    </div>
@endsection
