@extends('layouts.app')

@section('content')
<div class="container">
    <div class="result-header">
        <div class="result-title-area">
            <h2 class="result-title">お気に入り
                @if ($tools->isEmpty())
                はありません。
                @else
                &emsp;{{ $tools->count() }}件あります。</h2>
            @endif
        </div>

        <div class="sort-filter-bar">
            <div class="sort-options">
                @php
                $currentSort = request()->query('sort');
                $currentOrder = request()->query('order', 'asc');
                $nextOrder = $currentOrder === 'asc' ? 'desc' : 'asc';
                @endphp

                <a href="{{ route('favorites.search', ['sort' => 'date', 'order' => ($currentSort === 'date' ? $nextOrder : 'asc')]) }}" class="sort-button">
                    {!! $currentSort === 'date' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' !!} 公開日</a>
                <a href="{{ route('favorites.search', ['sort' => 'code', 'order' => ($currentSort === 'code' ? $nextOrder : 'asc')]) }}" class="sort-button">
                    {!! $currentSort === 'code' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' !!} ツールコード</a>
            </div>
        </div>
    </div>

    @if (!$tools->isEmpty())
    <div class="tool-grid">
        @foreach ($tools as $tool)
        <div class="tool-card">
            @php
            $pdfUrl = asset('storage/' . $tool->TOOL_PDF_FILE);
            @endphp

            <div class="thumbnail" onclick="loadPdf('{{ $pdfUrl }}')">
                <img src="{{ asset('storage/' . $tool->TOOL_THUM_FILE) }}" alt="サムネイル">
            </div>

            <div class="tool-info">
                <div class="tool-code">
                    ツールコード：{{ $tool->TOOL_CODE }}
                </div>

                <div class="tool-name">
                    <a href="{{ route('tools.show', ['code' => $tool->TOOL_CODE]) }}">
                        {{ $tool->TOOL_NAME }}
                    </a>
                </div>

                <div class="tool-actions">
                    <div class="quantity-control">
                        <input type="text" value="1" class="quantity-visible-input"
                            oninput="toHalfWidth(this)"
                            onkeydown="return isNumberKey(event)"
                            inputmode="numeric">
                        <span class="unit-label">{{ $tool->unit_name }}</span>
                        <button onclick="updateQuantity(this, -1)">－</button>
                        <button onclick="updateQuantity(this, 1)">＋</button>
                    </div>

                    <div class="action-buttons">
                        <form action="{{ route('favorites.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="TOOL_CODE" value="{{ $tool->TOOL_CODE }}">
                            <button type="submit" class="favorite-del">削除</button>
                        </form>

                        <a href="{{ route('tools.show', ['code' => $tool->TOOL_CODE, 'from' => 'favorites']) }}"
                            class="btn btn-success">ツール詳細</a>
                        <form action="{{ route('cart.add') }}" method="POST" class="cart-form">
                            @csrf
                            <input type="hidden" name="TOOL_CODE" value="{{ $tool->TOOL_CODE }}">
                            <input type="hidden" name="QUANTITY" value="1" class="cart-hidden-quantity">
                            <button type="button" class="btn btn-primary-favorite cart-button"
                                onclick="syncQuantityAndSubmit(this)">カートに入れる</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@if(session('cart_added_tool'))
<div id="cartModal" class="cart-modal">
    <h3>カートに追加しました</h3>
    <p><strong>{{ session('cart_added_tool')->TOOL_NAME }}</strong></p>
    <p>数量：{{ session('cart_added_quantity') }} {{ session('cart_added_tool')->unit_name }}</p>
    <div style="margin-top: 1rem;">
        <button onclick="document.getElementById('cartModal').style.display='none'" class="btn btn-primary">閉じる</button>
    </div>
</div>
@endif

<!-- PDFモーダル -->
<div id="pdfModal">
    <div style="position: relative; width: 100%; height: 100%;">
        <iframe id="pdfViewer" src=""></iframe>
        <button class="close-btn" onclick="closePdfModal()">✕</button>
    </div>
</div>

<script>
    function loadPdf(pdfUrl) {
        document.getElementById('pdfViewer').src = pdfUrl;
        document.getElementById('pdfModal').style.display = 'block';
    }

    function closePdfModal() {
        document.getElementById('pdfModal').style.display = 'none';
        document.getElementById('pdfViewer').src = '';
    }

    function updateQuantity(button, change) {
        const input = button.parentElement.querySelector('input');
        let value = parseInt(input.value.replace(/[^0-9]/g, '')) || 1;
        value += change;
        if (value < 1) value = 1;
        input.value = value;
    }

    function toHalfWidth(input) {
        input.value = input.value.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
            return String.fromCharCode(s.charCodeAt(0) - 65248);
        }).replace(/[^0-9]/g, '');
    }

    function isNumberKey(evt) {
        const charCode = evt.which || evt.keyCode;
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

    function syncQuantityAndSubmit(button) {
        const toolCard = button.closest('.tool-card');
        const visibleInput = toolCard.querySelector('.quantity-visible-input');
        const hiddenInput = toolCard.querySelector('.cart-hidden-quantity');
        const form = toolCard.querySelector('.cart-form');
        
        const value = parseInt(visibleInput.value.replace(/[^0-9]/g, '')) || 1;
        hiddenInput.value = value;

        button.closest('form').submit();
    }
</script>
@endsection