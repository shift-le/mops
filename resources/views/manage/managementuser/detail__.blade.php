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
<h3><br>基本情報</h3>

<div class="content-box">
    <form method="POST" action="{{ route('managementuser.update', ['id' => $user->USER_ID]) }}">
        @csrf
        @method('PUT')
        <div class="form-row">
            <label>社員ID</label>
            <input type="text" name="USER_ID" class="text-input" value="{{ $user->USER_ID }}" readonly>
        </div>

        <div class="form-row">
            <label>氏名</label>
            <input type="text" name="NAME" class="text-input" value="{{ $user->NAME }}" required>
        </div>

        <div class="form-row">
            <label>氏名カナ</label>
            <input type="text" name="NAME_KANA" class="text-input" value="{{ $user->NAME_KANA }}" required>
        </div>

        <div class="form-row">
            <label>メールアドレス</label>
            <input type="email" name="EMAIL" class="text-input" value="{{ $user->EMAIL }}" required>
        </div>

        <div class="form-row">
            <label>携帯電話番号</label>
            <input type="text" name="MOBILE_TEL" class="text-input" value="{{ $user->MOBILE_TEL }}">
        </div>

        <div class="form-row">
            <label>携帯メールアドレス</label>
            <input type="text" name="MOBILE_EMAIL" class="text-input" value="{{ $user->MOBILE_EMAIL }}">
        </div>

        <div class="form-row">
            <label>支店・部</label>
            <input type="text" name="SHITEN_BU_CODE" class="text-input" value="{{ $user->SHITEN_BU_CODE }}">
        </div>

        <div class="form-row">
            <label>営業所・グループ</label>
            <input type="text" name="EIGYOSHO_GROUP_CODE" class="text-input" value="{{ $user->EIGYOSHO_GROUP_CODE }}">
        </div>

        <div class="form-row btn-row">
            <button type="reset" class="btn-clear">キャンセル</button>
            <button type="submit" class="submit">更新する</button>
        </div>

        <h3>駐在員届け先情報</h3>
        <div class="content-box">
            <div class="form-row">
                <label>
                    <input type="checkbox" id="resident_check" name="is_thuzaiin" value="1"
                        {{ isset($thuzaiin) ? 'checked' : '' }}> 駐在員
                </label>
            </div>

            <div id="resident_fields" style="display:none;">
                <div class="form-row">
                    <label>届け先名称</label>
                    <input type="text" name="THUZAIIN_NAME" class="text-input"
                        value="{{ $thuzaiin->THUZAIIN_NAME ?? '' }}" required>
                </div>

                <div class="form-row">
                    <label>郵便番号</label>
                    <input type="text" name="POST_CODE1" class="text-input"
                        value="{{ $thuzaiin->POST_CODE1 ?? '' }}">
                </div>

            <div class="form-row">
                <label>都道府県</label>
                <select name="THUZAIIN_PREF" class="text-input" required>
                    <option value="">選択</option>
                    @foreach($prefectures as $pref)
                        <option value="{{ $pref->PREFECTURE_KEY }}"
                            {{ (isset($thuzaiin) && $thuzaiin->PREF_ID == $pref->PREFECTURE_KEY) ? 'selected' : '' }}>
                            {{ $pref->PREFECTURE_VALUE }}
                        </option>
                    @endforeach
                </select>
            </div>


                <div class="form-row">
                    <label>住所1</label>
                    <input type="text" name="ADDRESS1" class="text-input" 
                        value= "{{ $thuzaiin->ADDRESS1 ?? '' }}" required>
                </div>

                <div class="form-row">
                    <label>住所2</label>
                    <input type="text" name="ADDRESS2" class="text-input"
                        value="{{ $thuzaiin->ADDRESS2 ?? '' }}">
                </div>

                <div class="form-row">
                    <label>住所3</label>
                    <input type="text" name="ADDRESS3" class="text-input"
                        value="{{ $thuzaiin->ADDRESS3 ?? '' }}">
                </div>

                <div class="form-row">
                    <label>電話番号</label>
                    <input type="text" name="TEL" class="text-input" 
                        value="{{ $thuzaiin->TEL ?? '' }}" required>
                </div>
            </div>
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

  // 初期表示制御
  const isChecked = residentCheck.checked;
  document.getElementById('resident_fields').style.display = isChecked ? 'block' : 'none';
  const fields = document.querySelectorAll('#resident_fields input, #resident_fields select');
  fields.forEach(function(field) {
    field.required = isChecked;
  });
}
</script>
@endsection

