@extends('layouts.app')

@section('content')
<div class="main">
    <h2>注文履歴検索</h2>
    <form method="POST" action="{{ route('ordhistory.result') }}">
        @csrf
        <div class="search-bar" style="flex-wrap: wrap; gap: 1rem;">
            <input type="text" name="order_id" placeholder="注文ID">
            <input type="text" name="tool_code" placeholder="ツールコード">
            <input type="text" name="tool_name" placeholder="ツール名">

            <select name="order_status">
                <option value="">注文ステータス</option>
                <option value="注文受付">注文受付</option>
                <option value="発送済">発送済</option>
            </select>
@if ($errors->any())
    <div class="checkout-red">
        <ul style="margin: 0; padding-left: 1.2rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div class="ordhistory-date-range">
                <label for="start_date">注文日</label>
                <div class="ordhistory-date-input-wrapper">
                    <input type="text" id="start_date" name="start_date" class="search-date" placeholder="開始日">
                    <img src="{{ asset('assets/img/icon/calendar_black.png') }}" class="ordhistory-calendar-icon">
                </div>
                <span>〜</span>
                <div class="ordhistory-date-input-wrapper">
                    <input type="text" id="end_date" name="end_date" class="search-date" placeholder="終了日">
                    <img src="{{ asset('assets/img/icon/calendar_black.png') }}" class="ordhistory-calendar-icon">
                </div>
            </div>

            <button type="reset" class="btn">リセット</button>
            <button type="submit" class="btn btn-primary">検索する</button>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script>
    flatpickr("#start_date", { locale: "ja", dateFormat: "Y年m月d日" });
    flatpickr("#end_date", { locale: "ja", dateFormat: "Y年m月d日" });
</script>
@endsection
