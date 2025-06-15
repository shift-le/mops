@extends('layouts.app')

@section('content')

<h2>注文履歴検索</h2>

<form method="POST" action="{{ route('ordhistory.result') }}">
    @csrf
    <table class="orddetail-table" style="margin-bottom: 2rem;">
        <tr>
            <th>注文ID</th>
            <td><input type="text" name="order_id" placeholder="注文ID" value="{{ old('order_id') }}"></td>
        </tr>
        <tr>
            <th>ツールコード</th>
            <td colspan="2"><input type="text" name="TOOL_CODE" placeholder="ツールコード" value="{{ old('TOOL_CODE') }}"></td>
            <th>ツール名</th>
            <td><input type="text" name="tool_name" placeholder="ツール名" value="{{ old('tool_name') }}"></td>
        </tr>
        <tr>
            <th>注文ステータス</th>
            <td>
                <select name="order_status">
                    <option value="">選択してください</option>
                    <option value="注文受付" {{ old('order_status') == '注文受付' ? 'selected' : '' }}>注文受付</option>
                    <option value="発送済" {{ old('order_status') == '発送済' ? 'selected' : '' }}>発送済</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>注文日</th>
            <td>
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
                    <div class="ordhistory-date-input-wrapper">
                        <input type="text" id="start_date" name="start_date" class="search-date" placeholder="開始日" value="{{ old('start_date') }}">
                        <img src="{{ asset('assets/img/icon/calendar_black.png') }}" class="ordhistory-calendar-icon">
                    </div>
                    <span>〜</span>
                    <div class="ordhistory-date-input-wrapper">
                        <input type="text" id="end_date" name="end_date" class="search-date" placeholder="終了日" value="{{ old('end_date') }}">
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
</script>

@endsection