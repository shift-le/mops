@extends('layouts.manage')

@section('page_title', '掲示板管理')

@section('content')
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementboard.index') }}" class="tab-button {{ request()->routeIs('managementboard.index') ? 'active' : '' }}">一覧</a>
        <a href="{{ route('managementboard.create') }}" class="tab-button {{ request()->routeIs('managementboard.create') ? 'active' : '' }}">新規</a>
    </div>
</div>

<h2>掲示板 新規作成</h2>

<p style="
    margin: 20px 0;
    padding: 12px;
    border: 2px solid #006400;
    color: #006400;
    background-color: #e6f4e6;
    border-radius: 6px;
    font-weight: bold;
    text-align: left;
">この内容でよろしければ、「確認画面へ」を押してください。</p>

<form method="POST" action="{{ route('managementboard.confirm') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <table class="tool-detail-table">
            <tr>
                <th>掲載開始日</th>
                <td><input type="date" name="KEISAI_START_DATE" class="text-input" style="width: 80%;" required></td>
                <th>掲載終了日</th>
                <td><input type="date" name="KEISAI_END_DATE" class="text-input" style="width: 80%;" required></td>
            </tr>
            <tr>
                <th>重要度</th>
                <td colspan="3">
                    <label><input type="radio" name="JUYOUDO_STATUS" value="0" checked>通常</label>
                    <label><input type="radio" name="JUYOUDO_STATUS" value="1">緊急</label>
                </td>
            </tr>
            <tr>
                <th>タイトル</th>
                <td colspan="3">
                    <input type="text" name="KEIJIBAN_TITLE" class="text-input" style="width: 90%;" required>
                </td>
            </tr>
            <tr>
                <th>カテゴリー</th>
                <td colspan="3">
                    <label><input type="radio" name="KEIJIBAN_CATEGORY" value="0" checked> GUIDE</label>
                    <label><input type="radio" name="KEIJIBAN_CATEGORY" value="1"> INFO</label>
                </td>
            </tr>
            <tr>
                <th>内容</th>
                <td colspan="3">
                    <textarea name="KEIJIBAN_TEXT" class="text-input" rows="6" style="width: 90%; resize: none;" required>{{ old('KEIJIBAN_TEXT') }}</textarea>
                </td>
            </tr>

            {{-- 添付ファイル入力欄 --}}
            <tr>
                <th>添付ファイル</th>
                <td colspan="3">
                    <input type="file" name="attachment[]" multiple accept=".pdf,.jpg,.png" class="text-input">
                    <p class="note">※ 最大5件まで添付できます（PDFなど）</p>

                    @if (session()->has('attachment_paths'))
                        <ul style="margin-top: 10px;">
                            @foreach (session('attachment_paths') as $path)
                                <li>{{ basename($path) }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>

            <tr>
                <th>表示</th>
                <td colspan="3">
                    <label><input type="radio" name="HYOJI_FLG" value="1" checked> 表示</label>
                    <label><input type="radio" name="HYOJI_FLG" value="0"> 非表示</label>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <input type="hidden" name="mode" value="create">
        <div class="form-row btn-row" style="text-align: center; margin-top: 20px;">
            <button type="reset" class="btn-clear">キャンセル</button>
            <button type="submit" class="submit" style="margin-left: 20px;">確認画面へ</button>
        </div>
    </div>
</form>

<script>
function validateFiles() {
    const maxSize = 5 * 1024 * 1024; // 5MB
    const files = document.getElementById('attachment').files;
    for (let file of files) {
        if (file.size > maxSize) {
            alert(`${file.name} は5MBを超えています。`);
            document.getElementById('attachment').value = ''; // リセット
            return;
        }
    }
}
</script>
@endsection
