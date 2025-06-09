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

    <div class="content-box">
        <h3>基本情報</h3>

        <div class="form-row">
            <label>社員ID</label>
            <input type="text" name="USER_ID" class="text-input" required>
        </div>

        <div class="form-row">
            <label>社員番号</label>
            <input type="text" name="SHAIN_ID" class="text-input" required>
        </div>

        <div class="form-row">
            <label>氏名</label>
            <input type="text" name="NAME" class="text-input" required>
        </div>

        <div class="form-row">
            <label>氏名カナ</label>
            <input type="text" name="NAME_KANA" class="text-input" required>
        </div>

        <div class="form-row">
            <label>メールアドレス</label>
            <input type="email" name="EMAIL" class="text-input">
        </div>

        <div class="form-row">
            <label>携帯電話番号</label>
            <input type="text" name="MOBILE_TEL" class="text-input" required>
        </div>

        <div class="form-row">
            <label>携帯メールアドレス</label>
            <input type="email" name="MOBILE_EMAIL" class="text-input">
        </div>

        <div class="form-row">
            <label>支店・部</label>
            <select name="SHITEN_BU_CODE" class="text-input">
                <option value="">選択</option>
                @foreach ($branchList as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <label>営業所・グループ</label>
            <select name="EIGYOSHO_GROUP_CODE" class="text-input">
                <option value="">選択</option>
                @foreach ($officeList as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <h3>駐在員届け先情報</h3>
    <div class="content-box">
        <div class="form-row">
            <label>
                <input type="checkbox" id="resident_check" name="is_thuzaiin" value="1"> 駐在員
            </label>
        </div>

        <div id="resident_fields" style="display:none;">
            <div class="form-row">
                <label>届け先名称</label>
                <input type="text" name="THUZAIIN_NAME" class="text-input" required>
            </div>

            <div class="form-row">
                <label>郵便番号</label>
                <input type="text" name="THUZAIIN_ZIP" class="text-input">
            </div>

            <div class="form-row">
                <label>都道府県</label>
                <select name="THUZAIIN_PREF" class="text-input" required>
                    <option value="">選択</option>
                    <option value="東京都">東京都</option>
                    <option value="大阪府">大阪府</option>
                </select>
            </div>

            <div class="form-row">
                <label>住所1</label>
                <input type="text" name="THUZAIIN_ADDRESS1" class="text-input" required>
            </div>

            <div class="form-row">
                <label>住所2</label>
                <input type="text" name="THUZAIIN_ADDRESS2" class="text-input">
            </div>

            <div class="form-row">
                <label>住所3</label>
                <input type="text" name="THUZAIIN_ADDRESS3" class="text-input">
            </div>

            <div class="form-row">
                <label>電話番号</label>
                <input type="text" name="THUZAIIN_TEL" class="text-input" required>
            </div>
        </div>
    </div>

    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>

<script>
document.getElementById('resident_check').addEventListener('change', function () {
    document.getElementById('resident_fields').style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
