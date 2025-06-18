@extends('layouts.manage')

@section('content')

<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementorder.index') }}" class="tab-button {{ request()->routeIs('managementorder.index') ? 'active' : '' }}">検索・一覧</a>
    </div>
</div>

<h2>受注情報 一覧</h2>

<!-- 検索フォーム -->
<div class="search-box">
    <form method="GET" action="{{ route('managementorder.index') }}">

        {{-- ① 注文日時 --}}
        <div style="background-color: #5481DE; padding: 10px; border-radius: 6px; color: #fff; margin-bottom: 16px;">
            <h3 style="margin: 0 0 8px 0;">注文日時</h3>
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="display: flex; flex-direction: column;">
                    <label>開始日</label>
                    <input type="date" name="CREATE_DT" value="{{ request('CREATE_DT') }}" class="text-input" style="padding: 6px;">
                </div>
                <p>～</p>
                <div style="display: flex; flex-direction: column;">
                    <label>終了日</label>
                    <input type="date" name="UPDATE_DT" value="{{ request('UPDATE_DT') }}" class="text-input" style="padding: 6px;">
                </div>
            </div>
        </div>

        {{-- ② 注文情報 --}}
        <div style="margin-bottom: 16px;">
            <h3>注文情報</h3>
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>注文ID</label>
                    <input type="text" name="ORDER_CODE" value="{{ request('ORDER_CODE') }}" class="text-input" style="padding: 6px;">
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>ツールコード</label>
                    <input type="text" name="TOOL_CODE" value="{{ request('TOOL_CODE') }}" class="text-input" style="padding: 6px;">
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>ツール名</label>
                    <input type="text" name="TOOL_NAME" value="{{ request('TOOL_NAME') }}" class="text-input" style="padding: 6px;">
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>注文ステータス</label>
                    <select name="ORDER_STATUS" class="select-input" style="padding: 6px;">
                        <option value="">選択してください</option>
                        <option value="1" {{ request('ORDER_STATUS') == '1' ? 'selected' : '' }}>注文確認中</option>
                        <option value="2" {{ request('ORDER_STATUS') == '2' ? 'selected' : '' }}>制作中</option>
                        <option value="3" {{ request('ORDER_STATUS') == '3' ? 'selected' : '' }}>完了</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ③ 注文者情報 --}}
        <div style="margin-bottom: 16px;">
            <h3 style="margin-bottom: 8px;">注文者情報</h3>
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>組織１</label>
                    <select name="branch" class="select-input" style="padding:6px;">
                        <option value="">支店・部</option>
                        @foreach ($branchList as $code => $name)
                            <option value="{{ $code }}" {{ request('branch') == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>組織２</label>
                    <select name="office" class="select-input" style="padding:6px;">
                        <option value="">営業所・グループ</option>
                        @foreach ($officeList as $code => $name)
                            <option value="{{ $code }}" {{ request('office') == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>注文者名</label>
                    <input type="text" name="ORDER_NAME" value="{{ request('ORDER_NAME') }}" class="text-input" style="padding: 6px;">
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <label>社員ID</label>
                    <input type="text" name="USER_ID" value="{{ request('USER_ID') }}" class="text-input" style="padding: 6px;">
                </div>
            </div>
        </div>

        {{-- ④ 水平線 --}}
        <hr style="margin: 24px 0; border: none; border-top: 1px solid #ccc;">

        {{-- ⑤ ボタン配置 --}}
        <div style="display: flex; justify-content: center; gap: 20px;">
            <a href="{{ route('managementorder.index') }}" class="btn-clear" style="padding: 8px 20px; background: #6c757d; color: #fff; border-radius: 4px; text-decoration: none;">検索条件をクリア</a>
            <button type="submit" class="submit" style="padding: 8px 20px; background: #007bff; color: #fff; border: none; border-radius: 4px;">検索</button>
        </div>
    </form>
</div>


<!-- 一覧テーブル -->
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
        <tr style="color:#fff;">
            @php
                $currentSort = request('sort', 'CREATE_DT');
                $currentOrder = request('order', 'desc');
                $toggleOrder = fn($field) => ($currentSort === $field && $currentOrder === 'asc') ? 'desc' : 'asc';
                $orderIcon = fn($field) => $currentSort === $field ? ($currentOrder === 'asc' ? '▲' : '▼') : '';
            @endphp

            <th>
                <a href="{{ route('managementorder.index', array_merge(request()->query(), ['sort' => 'ORDER_CODE', 'order' => $toggleOrder('ORDER_CODE')])) }}" style="color:#fff; text-decoration:none;">
                    注文ID {!! $orderIcon('ORDER_CODE') !!}
                </a>
            </th>

            <th>
                <a href="{{ route('managementorder.index', array_merge(request()->query(), ['sort' => 'ORDER_NAME', 'order' => $toggleOrder('ORDER_NAME')])) }}" style="color:#fff; text-decoration:none;">
                    注文者名 {!! $orderIcon('ORDER_NAME') !!}
                </a>
            </th>

            <th>
                <a href="{{ route('managementorder.index', array_merge(request()->query(), ['sort' => 'CREATE_DT', 'order' => $toggleOrder('CREATE_DT')])) }}" style="color:#fff; text-decoration:none;">
                    注文日時 {!! $orderIcon('CREATE_DT') !!}
                </a>
            </th>

            <th>
                <a href="{{ route('managementorder.index', array_merge(request()->query(), ['sort' => 'ORDER_STATUS', 'order' => $toggleOrder('ORDER_STATUS')])) }}" style="color:#fff; text-decoration:none;">
                    注文ステータス {!! $orderIcon('ORDER_STATUS') !!}
                </a>
            </th>

            <th>ツールコード</th>
            <th>ツール名</th>
            <th>数量</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}">
                            {{ $order->ORDER_CODE }}
                        </a>
                    </td>
                    <td>{{ $order->ORDER_NAME }}</td>
                    <td>{{ $order->CREATE_DT }}</td>
                    <td>
                        @if ($order->ORDER_STATUS == '0')
                            出荷済
                        @elseif ($order->ORDER_STATUS == '1')
                            印刷作業中
                        @else
                            その他
                        @endif
                    </td>
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
                    <td style="display: flex; gap: 6px;">
                        <a href="{{ route('managementorder.show', ['id' => $order->ORDER_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #fff; color: #007bff; border-radius: 4px;">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

@endsection
