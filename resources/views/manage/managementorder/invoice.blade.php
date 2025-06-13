<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>納品書</title>
    <style>
        @font-face {
            font-family: 'ipaexg';
            src: url("{{ storage_path('fonts/ipaexg.ttf') }}") format('truetype');
        }
        body, table, th, td, h3, thead, tr {
            font-family: 'ipaexg', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        h3 {
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h3>注文詳細</h3>
<table>
    <tr>
        <th>注文コード</th>
        <td>{{ $order->ORDER_CODE }}</td>
    </tr>
    <tr>
        <th>注文日時</th>
        <td>{{ $order->CREATE_DT }}</td>
    </tr>
    <tr>
        <th>注文者</th>
        <td>{{ $order->ORDER_NAME }}（{{ $user->NAME_KANA }}） 社員ID: {{ $order->USER_ID }}<br>{{ $branchName }} {{ $officeName }}</td>
    </tr>
    <tr>
        <th>送付先</th>
        <td>{{ $order->ORDER_NAME }}（{{ $user->NAME_KANA }}）<br>{{ $order->ORDER_ADDRESS }}<br>{{ $branchName }} {{ $officeName }} {{ $order->ORDER_PHONE }}</td>
    </tr>
</table>

<h3>注文内容</h3>
<table>
    <thead>
        <tr>
            <th>ツールコード</th>
            <th>ツール名</th>
            <th>数量</th>
            <th>単価</th>
            <th>金額</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tools as $tool)
        <tr>
            <td>{{ $tool->TOOL_CODE }}</td>
            <td>{{ $tool->TOOL_NAME ?? '不明' }}</td>
            <td>{{ $tool->TOOL_QUANTITY }}</td>
            <td>{{ number_format($tool->AMOUNT) }} 円</td>
            <td>{{ number_format($tool->SUBTOTAL) }} 円</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align: right;">合計</th>
            <th style="text-align: right;">{{ number_format($tools->sum('SUBTOTAL')) }} 円</th>
        </tr>
    </tfoot>
</table>

</body>
</html>
