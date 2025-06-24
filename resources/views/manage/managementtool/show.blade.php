@extends('layouts.manage')

@section('content')

<style>
    /* 2カラム用 */
.form-row-group {
    display: flex;
    gap: 20px;
}
.form-row-group .form-row {
    flex: 1;
}

/* アコーディオンの装飾 */
.accordion {
    border: 1px solid #ccc;
    border-radius: 6px;
    overflow: hidden;
}
.accordion-toggle {
    background: #f6f6f6;
    padding: 12px 16px;
    border: none;
    width: 100%;
    text-align: left;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}
.accordion-toggle:hover {
    background: #eaeaea;
}
.accordion-content {
    padding: 16px;
    border-top: 1px solid #ccc;
    display: none;
}
.accordion-content.show {
    display: block;
    animation: fadeIn 0.3s ease;
}
.text-input[textarea] {
  resize: none;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
        <a href="{{ route('managementtool.create') }}" class="tab-button">新規</a>
        <a href="{{ route('managementtool.import') }}" class="tab-button">インポート</a>
    </div>
</div>

<h2>ツール情報 編集</h2>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form method="POST" action="{{ route('managementtool.update', ['id' => $tool->TOOL_CODE]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- サムネイル --}}
    @if (!empty($tool->TOOL_THUM_FILE))
        <div style="margin-bottom:20px;">
            <img src="{{ asset($tool->TOOL_THUM_FILE) }}" alt="サムネイル画像" style="max-width:300px; height:auto; display:block;">
        </div>
    @endif

    <div class="content-box">
        <h3>基本情報</h3>

        @php
            $user = Auth::user();
            $role = $user->ROLE_ID ?? null;
            $currentStatus = $tool->TOOL_STATUS;

            // 全体ステータス定義
            $statusLabels = [
                0 => '表示',
                1 => '仮登録',
                2 => 'マルホ確認済み',
                3 => '中島準備完了',
                4 => '非表示',
            ];

            // 表示許可された選択肢を定義
            $allowedStatus = [];

            if ($role === 'MA01') {
                if ($currentStatus == 1) {
                    $allowedStatus = [2];
                } elseif ($currentStatus == 3) {
                    $allowedStatus = [0];
                }
            } elseif ($role === 'NA01') {
                if ($currentStatus == 2) {
                    $allowedStatus = [3];
                }
            }

            // 既存のステータスを強制的に追加（重複防止も含む）
            if (!in_array($currentStatus, $allowedStatus)) {
                $allowedStatus[] = $currentStatus;
            }

            // 重複除去・並び替え（任意）
            $allowedStatus = array_unique($allowedStatus);
        @endphp

        <div class="form-row">
            <label>ステータス</label>

            {{-- 条件に合ったステータスだけをラジオ表示（現在値含む） --}}
            @foreach ($statusLabels as $val => $label)
                @if(in_array($val, $allowedStatus))
                    <label>
                        <input type="radio" name="TOOL_STATUS" value="{{ $val }}" {{ $tool->TOOL_STATUS == $val ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                @endif
            @endforeach
        </div>



        {{-- 基本情報 --}}
        <div class="form-row"><label>ツール名</label><input type="text" name="TOOL_NAME" value="{{ $tool->TOOL_NAME }}" class="text-input" required></div>
        <div class="form-row"><label>ツール名カナ</label><input type="text" name="TOOL_NAME_KANA" value="{{ $tool->TOOL_NAME_KANA }}" class="text-input" required></div>
        <div class="form-row"><label>ツールコード</label><input type="text" name="TOOL_CODE" value="{{ $tool->TOOL_CODE }}" class="text-input" required></div>

        {{-- 領域・品名 --}}
        <div class="form-row-group">
            <div class="form-row">
                <label>領域</label>
                <select name="RYOIKI" class="text-input">
                    @foreach($ryoikis as $code => $name)
                        <option value="{{ $code }}" {{ $tool->RYOIKI == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label>品名</label>
                <select name="HINMEI" class="text-input">
                    @foreach($hinmeis as $code => $name)
                        <option value="{{ $code }}" {{ $tool->HINMEI == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        {{-- 区分 --}}
        <div class="form-row-group">
            <div class="form-row">
                <label>ツール区分１</label>
                <select name="TOOL_TYPE1" class="text-input">
                    <option value="">選択</option>
                    @foreach($toolType1s as $id => $name)
                        <option value="{{ $id }}" {{ $tool->TOOL_TYPE1 == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <label>ツール区分２</label>
                <select name="TOOL_TYPE2" class="text-input">
                    <option value="">選択</option>
                    @foreach($toolType2s as $id => $name)
                        <option value="{{ $id }}" {{ $tool->TOOL_TYPE2 == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- その他 --}}
        <div class="form-row">
            <label>ツール説明</label>
            <textarea name="TOOL_SETSUMEI4" class="text-input" rows="4" style="resize: none;">{{ $tool->TOOL_SETSUMEI4 }}</textarea>
        </div>

        <div class="form-row">
            <label>備考</label>
            <textarea name="REMARKS" class="text-input" rows="4" style="resize: none;">{{ $tool->REMARKS }}</textarea>
        </div>

        {{-- PDFサムネイル --}}
        <h3>PDF・サムネイル情報</h3>

        <div class="form-row">
            <label>PDFファイル</label>
            @if (!empty($tool->TOOL_PDF_FILE))
                <div style="margin-bottom:10px;">
                    <a href="{{ asset($tool->TOOL_PDF_FILE) }}" target="_blank">現在のPDFを開く</a>
                </div>
            @endif
            <input type="file" name="TOOL_PDF_FILE" accept=".pdf" class="text-input">
        </div>

        <div class="form-row">
            <label>サムネイル画像</label>
            @if (!empty($tool->TOOL_THUM_FILE))
                <div style="margin-bottom:10px;">
                    <img src="{{ asset($tool->TOOL_THUM_FILE) }}" alt="サムネイル画像" style="max-width:200px; height:auto; display:block;">
                </div>
            @endif
            <input type="file" name="TOOL_THUM_FILE" accept=".jpg,.jpeg,.png" class="text-input">
        </div>

        {{-- 価格情報 --}}
        <h3>価格情報</h3>
        <div class="form-row"><label>単価（円）</label><input type="number" name="TANKA" value="{{ old('TANKA', $tool->TANKA ?? 0) }}" class="text-input" min="0" required></div>

        {{-- 表示期間 --}}
        <h3>表示期間</h3>
        <div class="form-row" style="width: 40%;">
            <label></label>
            <input type="date" name="MOPS_START_DATE" value="{{ \Carbon\Carbon::parse($tool->MOPS_START_DATE)->format('Y-m-d') }}" class="text-input" required>
            <p>～</p>
            <input type="date" name="MOPS_END_DATE" value="{{ \Carbon\Carbon::parse($tool->MOPS_END_DATE)->format('Y-m-d') }}" class="text-input" required>
        </div>

        {{-- 管理メモ --}}
        <h3>管理メモ</h3>
        <div class="form-row">
            <label>管理メモ</label>
            <textarea name="ADMIN_MEMO" class="text-input" rows="4" style="resize: none;">{{ $tool->ADMIN_MEMO }}</textarea>
        </div>

        {{-- その他の情報アコーディオン --}}
        <h3 style="margin-top: 30px;">その他の情報</h3>
        <div class="accordion">
            <button type="button" class="accordion-toggle">＋ Massで連携されたその他の情報</button>
            <div class="accordion-content" style="display:none; margin-top: 10px;">
                <div class="content-box">
                    <div class="form-row"><label>MSTフラグ</label><input type="number" name="MST_FLG" value="{{ $tool->MST_FLG }}" class="text-input"></div>
                    <div class="form-row"><label>管理期限</label><input type="date" name="KANRI_LIMIT_DATE" value="{{ $tool->KANRI_LIMIT_DATE ? \Carbon\Carbon::parse($tool->KANRI_LIMIT_DATE)->format('Y-m-d') : '' }}" class="text-input"></div>
                    <div class="form-row"><label>第一組織</label><input type="text" name="SOSHIKI1" value="{{ $tool->SOSHIKI1 }}" class="text-input"></div>
                    <div class="form-row"><label>第二組織</label><input type="text" name="SOSHIKI2" value="{{ $tool->SOSHIKI2 }}" class="text-input"></div>
                    @for ($i = 1; $i <= 10; $i++)
                        <div class="form-row">
                            <label>ツール管理者{{ $i }} ID</label>
                            <input type="text" name="TOOL_MANAGER{{ $i }}_ID" value="{{ $tool->{'TOOL_MANAGER' . $i . '_ID'} }}" class="text-input">
                        </div>

                        <div class="form-row">
                            <label>ツール管理者{{ $i }} 氏名</label>
                            <input type="text" name="TOOL_MANAGER{{ $i }}_NAME" value="{{ $tool->{'TOOL_MANAGER' . $i . '_NAME'} }}" class="text-input">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- ボタン --}}
        <div class="form-row btn-row" style="margin-top:20px;">
            <button type="reset" class="btn-clear">キャンセル</button>
            <button type="submit" class="submit">確認画面へ</button>
        </div>
    </div>
</form>

{{-- 削除用フォーム --}}
<form action="{{ route('managementtool.delete', ['id' => $tool->TOOL_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="margin-top: 20px;">
    @csrf
    <button type="submit" class="btn-delete">削除する</button>
</form>

{{-- アコーディオン動作用JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.accordion-toggle');
    const content = document.querySelector('.accordion-content');
    toggle.addEventListener('click', function () {
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggle.textContent = '− 閉じる';
        } else {
            content.style.display = 'none';
            toggle.textContent = '＋ Massで連携されたその他の情報';
        }
    });
});
</script>
@endsection
