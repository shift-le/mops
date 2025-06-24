@extends('layouts.manage')

@section('content')
<h2>納品書プレビュー</h2>

<div class="content-box">
    <div id="invoice-content">
        <h3>注文情報</h3>
        <p>注文番号：{{ $order->ORDER_CODE }}</p>
        <p>注文日時：{{ $order->CREATE_DT }}</p>
        <p>注文者：{{ $user->NAME }}（{{ $user->NAME_KANA }}）社員ID：{{ $user->USER_ID }}</p>
        <p>{{ $branchName }} {{ $officeName }}</p>

        <h3>送付先</h3>
        <p>{{ $order->ORDER_NAME }}（{{ $user->NAME_KANA }}）</p>
        <p>{{ $order->ORDER_ADDRESS }}</p>
        <p>{{ $branchName }} {{ $officeName }} {{ $order->ORDER_PHONE }}</p>

        <h3>注文内容</h3>
        <table border="1" cellpadding="5">
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
                @foreach($tools as $tool)
                <tr>
                    <td>{{ $tool->TOOL_CODE }}</td>
                    <td>{{ $tool->TOOL_NAME }}</td>
                    <td>{{ $tool->QUANTITY }}</td>
                    <td>{{ $tool->TANKA }}円</td>
                    <td>{{ $tool->QUANTITY * $tool->TANKA }}円</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>合計</strong></td>
                    <td><strong>{{ $order->AMOUNT }}円</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:20px;">
    <button onclick="downloadPDF()">PDFとして保存</button>
</div>

<!-- JSでPDF作成 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    async function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const invoice = document.getElementById('invoice-content');

        const canvas = await html2canvas(invoice, {
            scale: 2  // 解像度アップ（オプション）
        });
        const imgData = canvas.toDataURL('image/png');

        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const imgProps = pdf.getImageProperties(imgData);
        const imgWidth = pdfWidth * 0.95;  // 少し余白を持たせて全体にフィット
        const imgHeight = (imgProps.height * imgWidth) / imgProps.width;
        const xOffset = (pdfWidth - imgWidth) / 2;

        pdf.addImage(imgData, 'PNG', xOffset, 10, imgWidth, imgHeight);
        pdf.save("納品書_{{ $order->ORDER_CODE }}.pdf");
    }

</script>
@endsection
