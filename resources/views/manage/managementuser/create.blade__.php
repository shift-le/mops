@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementuser.index') }}" class="tab-button {{ request()->routeIs('managementuser.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementuser.create') }}" class="tab-button {{ request()->routeIs('managementuser.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementuser.import') }}" class="tab-button">インポート</a>
        <a href="{{ route('managementuser.export') }}" class="tab-button">エクスポート</a>
    </div>
</div>

<h2>ユーザ情報 新規登録 入力</h2>

<form method="POST" action="{{ route('managementuser.store') }}">
    @csrf
    <input type="text" name="USER_ID" required>
    <!-- その他フィールド -->
    <button type="submit">確認画面へ</button>
</form>


<script>
document.getElementById('resident_check').addEventListener('change', function () {
    document.getElementById('resident_fields').style.display = this.checked ? 'table' : 'none';
});
</script>
@endsection
