@extends('layouts.manage')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementuser.index') }}" class="tab-button {{ request()->routeIs('managementuser.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementuser.create') }}" class="tab-button {{ request()->routeIs('managementuser.create') ? 'active' : '' }}">新規</a>
        <a href="{{ route('managementuser.import') }}" class="tab-button {{ request()->routeIs('managementuser.import') ? 'active' : '' }}">インポート</a>
        <a href="{{ route('managementuser.export') }}" class="tab-button {{ request()->routeIs('managementuser.export') ? 'active' : '' }}">エクスポート</a>
    </div>
</div>

<h2>ユーザ情報 詳細・編集</h2>

<div>
    <form method="POST" action="{{ route('managementuser.update', ['id' => $user->USER_ID]) }}">
        @csrf
        @method('PUT')

        <table class="tool-detail-table">
            <tbody>
                <tr>
                    <th>社員ID</th>
                    <td colspan="3"><input type="text" name="USER_ID" class="text-input" value="{{ $user->USER_ID }}" style="width:90%;" readonly></td>
                </tr>
                <tr>
                    <th>氏名</th>
                    <td colspan="3"><input type="text" name="NAME" class="text-input" value="{{ $user->NAME }}" style="width:90%;" required></td>
                </tr>
                <tr>
                    <th>氏名カナ</th>
                    <td colspan="3"><input type="text" name="NAME_KANA" class="text-input" value="{{ $user->NAME_KANA }}"  style="width:90%;" required></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td colspan="3"><input type="email" name="EMAIL" class="text-input" value="{{ $user->EMAIL }}"  style="width:90%;" required></td>
                </tr>
                <tr>
                    <th>携帯電話番号</th>
                    <td colspan="3"><input type="text" name="MOBILE_TEL" class="text-input" value="{{ $user->MOBILE_TEL }}"  style="width:90%;" ></td>
                </tr>
                <tr>
                    <th>携帯メールアドレス</th>
                    <td colspan="3"><input type="text" name="MOBILE_EMAIL" class="text-input" value="{{ $user->MOBILE_EMAIL }}"  style="width:90%;" ></td>
                </tr>
                <tr>
                    <th>支店・部</th>
                    <td>
                        <select name="SHITEN_BU_CODE" class="text-input" style="width:48%;">
                            <option value="">選択</option>
                            @foreach($branchList as $code => $name)
                                <option value="{{ $code }}" {{ $user->SHITEN_BU_CODE == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <th>営業所・グループ</th>
                    <td>
                        <select name="EIGYOSHO_GROUP_CODE" class="text-input" style="width:48%;">
                            <option value="">選択</option>
                            @foreach($officeList as $code => $name)
                                <option value="{{ $code }}" {{ $user->EIGYOSHO_GROUP_CODE == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
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
                        <label>
                            <input type="checkbox" id="resident_check" name="is_thuzaiin" value="1" {{ isset($thuzaiin) ? 'checked' : '' }}> 駐在員
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="resident_fields" style="display:none;">
            <table class="tool-detail-table">
                <tbody>
                    <tr>
                        <th>届け先名称</th>
                        <td colspan="3"><input type="text" name="THUZAIIN_NAME" class="text-input" value="{{ $thuzaiin->THUZAIIN_NAME ?? '' }}" style="width:90%;"  required></td>
                    </tr>
                    <tr>
                        <th>郵便番号</th>
                        <td>
                            <input type="text" name="POST_CODE1" class="text-input" value="{{ isset($thuzaiin->POST_CODE) ? substr($thuzaiin->POST_CODE, 0, 3) : '' }}" style="width:22%; margin-right:2%;" maxlength="3">
                            ー
                            <input type="text" name="POST_CODE2" class="text-input" value="{{ isset($thuzaiin->POST_CODE) ? substr($thuzaiin->POST_CODE, 3, 4) : '' }}" style="width:30%; margin-left:2%;" maxlength="4">
                        </td>
                        <th>都道府県</th>
                        <td>
                            <select name="THUZAIIN_PREF" class="text-input" style="width:48%;" required>
                            <option value="">選択</option>
                            @foreach($prefectures as $pref)
                                <option value="{{ $pref->PREFECTURE_KEY }}" {{ (isset($thuzaiin) && $thuzaiin->PREF_ID == $pref->PREFECTURE_KEY) ? 'selected' : '' }}>
                                    {{ $pref->PREFECTURE_VALUE }}
                                </option>
                            @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>住所1</th>
                        <td colspan="3"><input type="text" name="ADDRESS1" class="text-input" value="{{ $thuzaiin->ADDRESS1 ?? '' }}" style="width:90%;"  required></td>
                    </tr>
                    <tr>
                        <th>住所2</th>
                        <td colspan="3"><input type="text" name="ADDRESS2" class="text-input" value="{{ $thuzaiin->ADDRESS2 ?? '' }}" style="width:90%;" ></td>
                    </tr>
                    <tr>
                        <th>住所3</th>
                        <td colspan="3"><input type="text" name="ADDRESS3" class="text-input" value="{{ $thuzaiin->ADDRESS3 ?? '' }}" style="width:90%;" ></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td colspan="3"><input type="text" name="TEL" class="text-input" value="{{ $thuzaiin->TEL ?? '' }}"  style="width:90%;" required></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-row btn-row">
            <button type="reset" class="btn-clear">キャンセル</button>
            <button type="submit" class="submit">更新する</button>
        </div>

    </form>
</div>

<script>
const residentCheck = document.getElementById('resident_check');
if (residentCheck) {
    residentCheck.addEventListener('change', function() {
        const isChecked = this.checked;
        const fields = document.querySelectorAll('#resident_fields input, #resident_fields select');
        fields.forEach(function(field) {
            field.required = isChecked;
        });
        document.getElementById('resident_fields').style.display = isChecked ? 'block' : 'none';
    });
    const isChecked = residentCheck.checked;
    document.getElementById('resident_fields').style.display = isChecked ? 'block' : 'none';
    const fields = document.querySelectorAll('#resident_fields input, #resident_fields select');
    fields.forEach(function(field) {
        field.required = isChecked;
    });
}
</script>
@endsection
