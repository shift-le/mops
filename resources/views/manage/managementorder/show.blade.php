@extends('layouts.manage')

@section('content')

<!-- タブボタン -->
<div class="tab-wrapper">
    <div class="tab-container">
        <a href="{{ route('managementorder.index') }}" class="tab-button {{ request()->routeIs('managementorder.index') ? 'active' : '' }}">検索・一覧</a>
    </div>
</div>
<div class="content-box">
<h3>注文詳細</h3>

<div class="content-box">
    <div class="form-row">
        <label>注文情報</label>
        <p>{{ $order->ORDER_CODE }} 　　　(注文日時：{{ $order->CREATE_DT}})</p>
    </div>
    <div class="form-row">
        <label>注文者</label>
        <p>{{ $order->ORDER_NAME }} 　　　(仮：カタカナ) 　　　社員ID: {{$order->USER_ID}}<br>関西支店　大阪北営業所</p>
    </div>
    <div class="form-row">
        <label>送付先</label>
        <p>{{ $order->ORDER_NAME }} <br>{{ $order->ORDER_ADDRESS }} <br> {{ $order->ORDER_PHONE}}</p>
    </div>
</div>

<h3>注文内容</h3>
<div class="content-box">
    <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <thead style="background-color:rgb(82, 83, 85);">
            <tr style="color:#fff;">
                <th>ツールコード</th>
                <th>ツール名</th>
                <th>数量</th>
                <th>単価</th>
                <th>小計</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tools as $tool)
            <tr>
                <td>{{ $tool->TOOL_CODE }}</td>
                <td>{{ $detail->TOOL_NAME ?? '不明' }}<br></td>
                <td>{{ $tool->TOOL_QUANTITY }}</td>
                <td>{{ number_format($tool->AMOUNT) }} 円</td>
                <td>{{ number_format($tool->SUBTOTAL) }} 円</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">合計</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($tools->sum('SUBTOTAL')) }} 円</td>
            </tr>
        </tfoot>
    </table>
</div>
<br>
<div class="content-box">
    <p>備考欄</p>
    <textarea name="REMARKS" rows="4" cols="60" placeholder="備考が入ります備考が入ります備考が入ります備考が入ります備考が入ります備考が入ります"></textarea>
</div>

<h3>中島弘文堂印刷所通信欄</h3>
    <label>ステータス</label>
    <label><input type="radio" name="ORDER_STATUS" value="3" {{ $tool->ORDER_STATUS == 3 ? 'checked' : '' }}> 注文確認中</label>
    <label><input type="radio" name="ORDER_STATUS" value="1" {{ $tool->ORDER_STATUS == 1 ? 'checked' : '' }}> 印刷作業中</label>
    <label><input type="radio" name="ORDER_STATUS" value="2" {{ $tool->ORDER_STATUS == 2 ? 'checked' : '' }}> 出荷待ち</label>
    <label><input type="radio" name="ORDER_STATUS" value="0" {{ $tool->ORDER_STATUS == 0 ? 'checked' : '' }}> 出荷済み</label>
<div class="form-row">
    <label>管理者メモ</label>
    <textarea name="REMARKS" rows="4" cols="60" placeholder="PENDINGPENDING"></textarea>
</div>
</div>

<div class="form-row btn-row" style="margin-top: 20px;">
    <a href="{{ route('managementorder.index') }}" class="btn-clear">一覧に戻る</a>
</div>
<div class="form-row btn-row" style="margin-top: 20px;">
    <a href="{{ route('managementorder.index') }}" class="btn-clear">納品書出力？？</a>
</div>

@endsection
