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
                    <td>
                        {{ $tool->ryoikiFromHinmei->RYOIKI_NAME ?? '不明' }} ＞ 
                        {{ $tool->hinmeiFromRyoiki->HINMEI_NAME ?? '不明' }}
                    </td>
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
<a href="{{ request('from') === 'favorites' ? route('favorites.search') : session('last_tool_search_url', route('tools.search')) }}" class="btn btn-secondary">戻る</a>
        </div>
        <div class="tool-actions-center">
<button id="favorite-toggle-button"
    data-tool-code="{{ $tool->TOOL_CODE }}"
    data-is-favorite="{{ $tool->is_favorite ? '1' : '0' }}"
    class="btn {{ $tool->is_favorite ? 'btn-warning' : 'btn-outline-primary' }} favorite-button-actions" 
    style="height: 35px; line-height: 3px;">
    {{ $tool->is_favorite ? '❤️ お気に入り済み' : '♡ お気に入りに追加する' }}
</button>
        </div>
        <div class="tool-actions-right">
            <div class="quantity-control-actions">
                <input type="text" value="1"
                    oninput="toHalfWidth(this)"
                    onkeydown="return isNumberKey(event)"
                    inputmode="numeric">
                <span class="unit-label">{{ $tool->unit_name }}</span>
                <button onclick="updateQuantity(this, -1)">－</button>
                <button onclick="updateQuantity(this, 1)">＋</button>
            </div>
            <form id="cart-form" action="{{ route('cart.add') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="TOOL_CODE" value="{{ $tool->TOOL_CODE }}">
                <input type="hidden" name="QUANTITY" value="1" id="cart-quantity">
            </form>
            <button class="btn btn-primary-actions"
                onclick="addToCart('{{ $tool->TOOL_CODE }}')">カートに入れる</button>
        </div>
    </div>
</div>

@if(session('cart_added_tool'))
<div id="cartModal" class="cart-modal">
    <h3>カートに追加しました</h3>
    <p><strong>{{ session('cart_added_tool')->TOOL_NAME }}</strong></p>
    <p>数量：{{ session('cart_added_quantity') }} {{ session('cart_added_tool')->unit_name }}</p>
    <div>
        <button onclick="document.getElementById('cartModal').style.display='none'" class="btn btn-primary">閉じる</button>
    </div>
</div>
@endif

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

document.getElementById('favorite-toggle-button').addEventListener('click', function () {
    const button = this;
    const toolCode = button.getAttribute('data-tool-code');
    const isFavorite = button.getAttribute('data-is-favorite') === '1';

    fetch("{{ route('favorites.toggle') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ TOOL_CODE: toolCode })
    })
    .then(response => response.ok ? location.reload() : alert('更新に失敗しました'))
    .catch(() => alert('通信エラーが発生しました'));
});

    function addToCart(toolCode) {
        const input = document.querySelector('.quantity-control-actions input');
        const quantity = parseInt(input.value.replace(/[^0-9]/g, '')) || 1;
        document.getElementById('cart-quantity').value = quantity;
        document.getElementById('cart-form').submit();
    }
</script>

@endsection