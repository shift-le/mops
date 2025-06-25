@extends('layouts.manage')

@section('page_title', 'ユーザ情報管理')

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

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <h3>基本情報</h3>
    <table class="tool-detail-table">
        <tbody>
            <tr>
                <th>社員ID</th>
                <td colspan="3"><input type="text" name="USER_ID" class="text-input" style="width:90%;" required></td>
            </tr>
            <tr>
                <th>社員番号</th>
                <td colspan="3"><input type="text" name="SHAIN_ID" class="text-input" style="width:90%;" required></td>
            </tr>
            <tr>
                <th>氏名</th>
                <td colspan="3"><input type="text" name="NAME" class="text-input" style="width:90%;" required></td>
            </tr>
            <tr>
                <th>氏名カナ</th>
                <td colspan="3"><input type="text" name="NAME_KANA" class="text-input" style="width:90%;" required></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td colspan="3"><input type="email" name="EMAIL" class="text-input" style="width:90%;" ></td>
            </tr>
            <tr>
                <th>携帯電話番号</th>
                <td colspan="3"><input type="text" name="MOBILE_TEL" class="text-input"  style="width:90%;" required></td>
            </tr>
            <tr>
                <th>携帯メールアドレス</th>
                <td colspan="3"><input type="email" name="MOBILE_EMAIL" class="text-input" style="width:90%;" ></td>
            </tr>
            <tr>
                <th>支店・部</th>
                <td>
                    <select name="SHITEN_BU_CODE" class="text-input">
                        <option value="">選択</option>
                        @foreach ($branchList as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
                <th>営業所・グループ</th>
                <td>
                    <select name="EIGYOSHO_GROUP_CODE" class="text-input">
                        <option value="">選択</option>
                        @foreach ($officeList as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </tbody>
    </table>

    <h3>駐在員届け先情報</h3>
    <table class="tool-detail-table">
        <tbody>
            <tr>
                <th>駐在員</th>
                <td>
                    <!-- OFFでも送信させる -->
                    <input type="hidden" name="is_thuzaiin" value="0">
                    <label><input type="checkbox" id="resident_check" name="is_thuzaiin" value="1" {{ old('is_thuzaiin') ? 'checked' : '' }}> 駐在員</label>
                </td>
            </tr>
        </tbody>
    </table>

    <table id="resident_fields" class="tool-detail-table" style="display:none;">
        <tbody>
            <tr>
                <th>届け先名称</th>
                <td colspan="3"><input type="text" name="THUZAIIN_NAME" class="text-input"  style="width:90%;"></td>
            </tr>
            <tr>
                <th>郵便番号</th>
                <td><input type="text" name="THUZAIIN_ZIP" class="text-input"></td>
                <th>都道府県</th>
                <td>
                    <select name="THUZAIIN_PREF" class="text-input">
                        <option value="">選択</option>
                        @foreach($prefectures as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>住所1</th>
                <td colspan="3"><input type="text" name="THUZAIIN_ADDRESS1" class="text-input"  style="width:90%;"></td>
            </tr>
            <tr>
                <th>住所2</th>
                <td colspan="3"><input type="text" name="THUZAIIN_ADDRESS2" class="text-input" style="width:90%;" ></td>
            </tr>
            <tr>
                <th>住所3</th>
                <td colspan="3"><input type="text" name="THUZAIIN_ADDRESS3" class="text-input" style="width:90%;" ></td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td colspan="3"><input type="text" name="THUZAIIN_TEL" class="text-input"  style="width:90%;"></td>
            </tr>
        </tbody>
    </table>

    <div class="form-row btn-row">
        <button type="reset" class="btn-clear">キャンセル</button>
        <button type="submit" class="submit">確認画面へ</button>
    </div>
</form>

<script>
document.getElementById('resident_check').addEventListener('change', function () {
    document.getElementById('resident_fields').style.display = this.checked ? 'table' : 'none';
});
</script>
@endsection
