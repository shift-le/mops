@extends('layouts.app')

@section('content')
<div class="container tool-detail-container">
    <h2 class="tool-detail-title">ツール詳細</h2>

    <div class="tool-detail-card">
        <!-- 左：サムネイル -->
        <div class="tool-detail-thumbnail">
            <img src="{{ asset('storage/' . $tool->TOOL_THUM_FILE) }}" alt="ツールサムネイル">
        </div>

        <!-- 右：詳細情報 -->
        <div class="tool-detail-info">
<table class="tool-detail-table">
    <tr>
        <th rowspan="3">領域・品名</th>
        <td>{{ $tool->RYOIKI }} ＞ {{ $tool->hinmei->HINMEI_NAME ?? '不明' }}</td>
    </tr>
    <tr>
        <td>{{ $tool->sub_field1 ?? '　' }}</td>
    </tr>
    <tr>
        <td>{{ $tool->sub_field2 ?? '　' }}</td>
    </tr>
    <tr>
        <th>ツールコード</th>
        <td>{{ $tool->TOOL_CODE }}</td>
    </tr>
    <tr>
        <th>ツール名</th>
        <td>{{ $tool->TOOL_NAME }}</td>
    </tr>
@php
    $descLines = preg_split('/\r\n|\r|\n/', $tool->description ?? '');
@endphp

<tr>
    <th rowspan="3">ツール説明</th>
    <td>{{ $descLines[1] ?? 'なし' }}</td>
</tr>
<tr>
    <td>{{ $descLines[2] ?? '　' }}</td>
</tr>
<tr>
    <td>{{ $descLines[3] ?? '　' }}</td>
</tr>

</table>


        </div>
    </div>

    <!-- アクションボタン群 -->
    <div class="tool-detail-actions">
        <div class="tool-actions-left">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">戻る</a>
        </div>

    <div class="tool-actions-right">
                <button class="favorite-button-actions {{ $tool->is_favorite ? 'active' : '' }}"
                onclick="toggleFavorite('{{ $tool->TOOL_CODE }}', this)">
            <span class="icon">{{ $tool->is_favorite ? '❤️ お気に入り済み' : '♡ お気に入りに追加する' }}</span>
        </button>
        <div class="quantity-control-actions">
            <input type="text" value="1"
                oninput="toHalfWidth(this)"
                onkeydown="return isNumberKey(event)"
                inputmode="numeric">
            <span class="unit-label">{{ $tool->unit_name }}</span>
            <button onclick="updateQuantity(this, -1)">－</button>
            <button onclick="updateQuantity(this, 1)">＋</button>
        </div>

        <button class="btn btn-primary-actions"
                onclick="addToCart('{{ $tool->TOOL_CODE }}', this)">カートに入れる</button>
        </div>
    </div>
</div>

<script>
function updateQuantity(button, change) {
    const container = button.closest('.quantity-control-actions');
    const input = container.querySelector('input');
    let current = parseInt(input.value.replace(/[^0-9]/g, ''), 10);
    if (isNaN(current)) current = 1;
    let newValue = current + change;
    if (newValue < 1) newValue = 1;
    input.value = newValue;
}
</script>


@endsection
