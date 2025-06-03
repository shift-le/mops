@extends('layouts.manage')

@section('content')

<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementorder.index') }}" class="tab-button {{ request()->routeIs('managementorder.index') ? 'active' : '' }}">検索・一覧</a>
    </div>
</div>

<h2>注文詳細</h2>

<div class="content-box">
    <div class="form-row">
        <label>受注コード</label>
        <p>{{ $order->ORDER_CODE }}</p>
    </div>
    <div class="form-row">
        <label>依頼者名</label>
        <p>{{ $order->IRAI_NAME }}</p>
    </div>
    <div class="form-row">
        <label>注文者名</label>
        <p>{{ $order->ORDER_NAME }}</p>
    </div>
    <div class="form-row">
        <label>住所</label>
        <p>{{ $order->ORDER_ADDRESS }}</p>
    </div>
    <div class="form-row">
        <label>注文日時</label>
        <p>{{ $order->CREATE_DT }}</p>
    </div>
</div>

<h3>注文明細</h3>
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>ツールコード</th>
                <th>ツールID</th>
                <th>数量</th>
                <th>単価</th>
                <th>小計</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tools as $tool)
            <tr>
                <td>{{ $tool->TOOL_CODE }}</td>
                <td>{{ $tool->TOOLID }}</td>
                <td>{{ $tool->TOOL_QUANTITY }}</td>
                <td>{{ number_format($tool->AMOUNT) }} 円</td>
                <td>{{ number_format($tool->SUBTOTAL) }} 円</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="form-row btn-row" style="margin-top: 20px;">
    <a href="{{ route('managementorder.index') }}" class="btn-clear">一覧に戻る</a>
</div>

@endsection
