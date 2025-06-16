@extends('layouts.app')

@section('content')

<h2>注文履歴検索</h2>

<form method="POST" action="{{ route('ordhistory.result') }}">
    @csrf
    <table class="orddetail-table" style="margin-bottom: 2rem;">
        <tr>
            <th style="text-align: center;">注文ID</th>
            <td colspan="4"><input type="text" name="order_id" value="{{ $inputs['order_id'] ?? '' }}" style="min-width: 33%; padding: 12px; border:1px solid #ccc"></td>
        </tr>
        <tr>
            <th style="text-align: center;">ツールコード</th>
            <td colspan="2" style="border-right: none;"><input type="text" name="TOOL_CODE" value="{{ old('TOOL_CODE') }}" style="min-width: 74%; padding: 12px; border:1px solid #ccc"></td>
            <th style="text-align: center;">ツール名</th>
            <td><input type="text" name="tool_name" value="{{ old('tool_name') }}" style="min-width: 74%; padding: 12px 0; border:1px solid #ccc"></td>
        </tr>
        <tr>
            <th style="text-align: center;">注文ステータス</th>
            <td colspan="4">
                <select name="order_status" style="border: 1px solid #ccc; min-width: 35%; padding: 12px;">
                    <option value="">選択してください</option>
                    <option value="注文受付" {{ old('order_status') == '注文受付' ? 'selected' : '' }}>注文受付</option>
                    <option value="発送済" {{ old('order_status') == '発送済' ? 'selected' : '' }}>発送済</option>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align: center;">注文日</th>
            <td colspan="4">
                @if ($errors->any())
                <div class="checkout-red">
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div class="ordhistory-date-input-wrapper" style="border: 1px solid #ccc; min-width: 35%;">
                        <input type="text" id="start_date" name="start_date" class="search-date" placeholder="開始日を設定" value="{{ $inputs['start_date'] ?? '' }}" style="width: 400px;">
                        <img src="{{ asset('assets/img/icon/calendar_black.png') }}" class="ordhistory-calendar-icon">
                    </div>
                    <span>〜</span>
                    <div class="ordhistory-date-input-wrapper" style="border: 1px solid #ccc; min-width: 35%;">
                        <input type="text" id="end_date" name="end_date" class="search-date" placeholder="終了日を設定" value="{{ $inputs['end_date'] ?? '' }}" style="width: 400px;">
                        <img src="{{ asset('assets/img/icon/calendar_black.png') }}" class="ordhistory-calendar-icon">
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="ordhistory-text-center">
        <button type="reset" class="reset-btn">リセット</button>
        <button type="submit" class="reset-btn-primary">検索する</button>
    </div>
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

<script>
    flatpickr("#start_date", {
        locale: "ja",
        dateFormat: "Y年m月d日"
    });
    flatpickr("#end_date", {
        locale: "ja",
        dateFormat: "Y年m月d日"
    });

    // ✅ リセットボタンで手動クリア
    document.querySelector('form').addEventListener('reset', function () {

        setTimeout(function () {
            document.querySelector('input[name="order_id"]').value = '';
            document.querySelector('input[name="TOOL_CODE"]').value = '';
            document.querySelector('input[name="tool_name"]').value = '';
            document.querySelector('select[name="order_status"]').value = '';
            document.querySelector('input[name="start_date"]').value = '';
            document.querySelector('input[name="end_date"]').value = '';
        }, 0);
    });
</script>


@endsection