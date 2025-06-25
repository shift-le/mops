@extends('layouts.manage')

@section('page_title', '受注情報管理')

@section('content')

<style>
    .pagination-wrapper svg {
        width: 20px !important;
        height: 20px !important;
    }

    .pagination-wrapper .flex {
        justify-content: center;
    }

    .pagination-wrapper .page-link {
        padding: 4px 8px;
        margin: 0 2px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #007bff;
        text-decoration: none;
    }

    .pagination-wrapper .page-link:hover {
        background-color: #f0f0f0;
    }

    .pagination-wrapper .active .page-link {
        font-weight: bold;
        background-color: #007bff;
        color: #fff;
    }

    .calendar-icon {
        position: absolute;
        right: 10px;
        top: 10px;
        width: 17px;
        font-size: 1rem;
        opacity: 0.3;
        pointer-events: none;
    }
    select.select-input option.placeholder {
        color: #999;
    }
</style>
    <!-- タブボタン -->
    <div class="tab-wrapper">
        <div class="tab-container" style="margin-bottom: 10px; border-bottom: none;">
            <a href="{{ route('managementorder.index') }}" class="tab-button {{ request()->routeIs('managementorder.index') ? 'active' : '' }}"
                style="background-color: #007bff; border-color: #007bff; border-radius: 1px; padding: 7px 15px; font-size: small;">
                検索・一覧</a>
        </div>
    </div>


<!-- 検索フォーム -->
<div class="search-box" style="border-radius: 1px;">
    <form method="GET" action="{{ route('managementorder.index') }}">

        {{-- ① 注文日時 --}}
        <div style="margin-bottom: 16px; background-color: #D9E5FF; padding: 10px;">
            <h3 style="margin: auto; font-size: small; font-weight: 100;">注文日時</h3>

            @if ($errors->has('date_range'))
            <div style="color: red; font-size: x-small; margin-top: 5px;">
                {{ $errors->first('date_range') }}
            </div>
            @endif

            <div style="display: flex; gap: 16px; align-items: center;">
                <select name="date_range" class="select-input" onchange="removePlaceholder(this)">
                    <option value="" disabled {{ request('date_range') == '' ? 'selected' : '' }} class="placeholder">選択してください</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>今日</option>
                    <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>前日</option>
                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>今月</option>
                    <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>前月</option>
                </select>

                <div style="position: relative;">
                    <input type="text" id="create-date" name="CREATE_DT" value="{{ request('CREATE_DT') }}" class="text-input" placeholder="開始日" style="padding-right: 30px;">
                    <img src="{{ asset('assets/img/icon/calendar_black.png') }}" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 18px; pointer-events: none;">
                </div>

                <span>～</span>

                <div style="position: relative;">
                    <input type="text" id="update-date" name="UPDATE_DT" value="{{ request('UPDATE_DT') }}" class="text-input" placeholder="終了日" style="padding-right: 30px;">
                    <img src="{{ asset('assets/img/icon/calendar_black.png') }}" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 18px; pointer-events: none;">
                </div>
            </div>
        </div>

        {{-- ② 注文情報 --}}
        <div style="margin-bottom: 16px;">
            <h3 style="margin: auto; font-size: small; font-weight: 100;">注文情報</h3>
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <input type="text" name="ORDER_CODE" placeholder="注文ID" value="{{ request('ORDER_CODE') }}" class="text-input">
                <input type="text" name="TOOL_CODE" placeholder="ツールコード" value="{{ request('TOOL_CODE') }}" class="text-input">
                <input type="text" name="TOOL_NAME" placeholder="ツール名" value="{{ request('TOOL_NAME') }}" class="text-input">
                <select name="ORDER_STATUS" class="select-input">
                    <option value="">注文ステータス</option>
                    <option value="1" {{ request('ORDER_STATUS') == '1' ? 'selected' : '' }}>注文確認中</option>
                    <option value="2" {{ request('ORDER_STATUS') == '2' ? 'selected' : '' }}>制作中</option>
                    <option value="3" {{ request('ORDER_STATUS') == '3' ? 'selected' : '' }}>完了</option>
                </select>
            </div>
        </div>

        {{-- ③ 注文者情報 --}}
        <div style="margin-bottom: 16px;">
            <h3 style="margin: auto; font-size: small; font-weight: 100;">注文者情報</h3>
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <select name="branch" class="select-input">
                    <option value="">支店・部</option>
                    @foreach ($branchList as $code => $name)
                    <option value="{{ $code }}" {{ request('branch') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="office" class="select-input">
                    <option value="">営業所・グループ</option>
                    @foreach ($officeList as $code => $name)
                    <option value="{{ $code }}" {{ request('office') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <input type="text" name="ORDER_NAME" placeholder="注文者名" value="{{ request('ORDER_NAME') }}" class="text-input">
                <input type="text" name="USER_ID" placeholder="社員ID" value="{{ request('USER_ID') }}" class="text-input">
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('managementorder.index') }}" class="btn-clear">クリア</a>
            <button type="submit" class="submit">検索</button>
        </div>
    </form>
</div>

<!-- 一覧テーブル -->
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85); color:#fff;">
            <tr>
                <th>注文ID</th>
                <th>注文者名</th>
                <th>注文日時</th>
                <th>注文ステータス</th>
                <th>ツールコード</th>
                <th>ツール名</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td><a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}">{{ $order->ORDER_CODE }}</a></td>
                <td>{{ $order->ORDER_NAME }}</td>
                <td>{{ $order->CREATE_DT }}</td>
                <td>{{ $order->ORDER_STATUS == '1' ? '注文確認中' : ($order->ORDER_STATUS == '2' ? '制作中' : '完了') }}</td>
                <td>
                    @foreach ($order->details as $detail)
                    {{ $detail->TOOLID }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach ($order->details as $detail)
                    {{ $detail->tool->TOOL_NAME ?? '不明' }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach ($order->details as $detail)
                    {{ $detail->QUANTITY }}<br>
                    @endforeach
                </td>
                <td><a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}" class="btn-detail">詳細</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $orders->appends(request()->query())->links('components.numeric') }}
    </div>
</div>

<!-- カレンダー設定 -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script>
    flatpickr("input[name='CREATE_DT']", {
        dateFormat: "Y-m-d",
        locale: "ja"
    });

    flatpickr("input[name='UPDATE_DT']", {
        dateFormat: "Y-m-d",
        locale: "ja"
    });

    function removePlaceholder(select) {
        for (const option of select.options) {
            option.classList.remove('placeholder');
        }
    }
</script>

@endsection
