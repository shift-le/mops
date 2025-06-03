@extends('layouts.app')

@section('content')

@if(Auth::check())
    <p>ログイン中：{{ Auth::id() }}</p>
@else
    <p>ログインしていません</p>
@endif

<div class="container">
    <div class="result-header">
        <div class="result-title-area">
            <h2 class="result-title">「{{ $hinmei->HINMEI_NAME }}」の検索結果一覧&emsp;{{ $tools->count() }}件</h2>
        </div>

        @if ($tools->isEmpty())
            <p>この品名に紐づくツールはありません。</p>
        @else

        <div class="sort-filter-bar">
            <div class="sort-options">
                @php
                    $currentSort = request()->query('sort');
                    $currentOrder = request()->query('order', 'asc');
                    $nextOrder = $currentOrder === 'asc' ? 'desc' : 'asc';
                @endphp

                <a href="{{ route('tools.search', ['hinmei' => $hinmei->HINMEI_CODE, 'sort' => 'date', 'order' => ($currentSort === 'date' ? $nextOrder : 'asc')]) }}" class="sort-button">
                    {!! $currentSort === 'date' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' !!} 公開日</a>
                <a href="{{ route('tools.search', ['hinmei' => $hinmei->HINMEI_CODE, 'sort' => 'code', 'order' => ($currentSort === 'code' ? $nextOrder : 'asc')]) }}" class="sort-button">
                    {!! $currentSort === 'code' ? ($currentOrder === 'asc' ? '↑' : '↓') : '' !!} ツールコード</a>

                <button class="sort-button">↑↓並び替え</button>
                <button class="sort-button">よく使われるツール</button>
            </div>
        </div>
    </div>

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
<form action="{{ route('favorites.toggle') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="tool_code" value="{{ $tool->TOOL_CODE }}">
    <button type="submit" class="favorite-button {{ $tool->is_favorite ? 'active' : '' }}">
        <span class="icon">{{ $tool->is_favorite ? '❤️' : '♡' }}</span>
    </button>
</form>
            <div class="thumbnail" onclick="loadPdf('{{ $pdfUrl }}')">
                <img src="{{ asset('storage/' . $tool->TOOL_THUM_FILE) }}" alt="サムネイル">
            </div>
            <div class="tool-info">
                <div class="tool-code">
                    ツールコード：{{ $tool->TOOL_CODE }}
                    <form action="{{ route('favorites.toggle') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="tool_code" value="{{ $tool->TOOL_CODE }}">
                        <button type="submit" class="favorite-button {{ $tool->is_favorite ? 'active' : '' }}">
                            <span class="icon">{{ $tool->is_favorite ? '♥' : '♡' }}</span>
                        </button>
                    </form>

                    </div>
                    <div class="tool-name">
                        <a href="{{ route('tools.show', ['code' => $tool->TOOL_CODE]) }}" class="#">{{ $tool->TOOL_NAME }}</a>
                    </div>

                    <div class="tool-actions">
                        <div class="quantity-control">
                            <input type="text" value="1"
                                oninput="toHalfWidth(this)"
                                onkeydown="return isNumberKey(event)"
                                inputmode="numeric">
                            <span class="unit-label">{{ $tool->unit_name }}</span>
                            <button type="button" onclick="updateQuantity(this, -1)">－</button>
                            <button type="button" onclick="updateQuantity(this, 1)">＋</button>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('tools.show', ['code' => $tool->TOOL_CODE]) }}" class="btn btn-success">ツール詳細</a>
                            <button type="button" class="btn btn-primary"
                                onclick="submitCartForm('{{ $tool->TOOL_CODE }}', this)">カートに入れる</button>

                            <form action="{{ route('cart.add') }}" method="POST" class="cart-form" style="display: none;">
                                @csrf
                                <input type="hidden" name="tool_code" value="{{ $tool->TOOL_CODE }}">
                                <input type="hidden" name="quantity" value="1">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>

<!-- PDFモーダル -->
<div id="pdfModal">
    <div style="position: relative; width: 100%; height: 100%;">
        <iframe id="pdfViewer" src=""></iframe>
        <button class="close-btn" onclick="closePdfModal()">✕</button>
    </div>
</div>

<!-- カート追加モーダル -->
@if(session('cart_added_tool'))
<div id="cartModal" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -20%);
    background: white; border: 2px solid #0099FF; padding: 2rem; z-index: 9999;
    box-shadow: 0 0 10px rgba(0,0,0,0.3); max-width: 90%; text-align: center;">
    <h3>カートに追加しました</h3>
    <p><strong>{{ session('cart_added_tool')->TOOL_NAME }}</strong></p>
    <p>数量：{{ session('cart_added_quantity') }} {{ session('cart_added_tool')->unit_name }}</p>
    <div style="margin-top: 1rem;">
        <button onclick="document.getElementById('cartModal').style.display='none'" class="btn btn-primary">閉じる</button>
    </div>
</div>
@endif

<script>
function loadPdf(pdfUrl) {
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfModal').style.display = 'block';
}

function closePdfModal() {
    document.getElementById('pdfModal').style.display = 'none';
    document.getElementById('pdfViewer').src = '';
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

function updateQuantity(button, change) {
    const input = button.parentElement.querySelector('input');
    let value = parseInt(input.value.replace(/[^0-9]/g, '')) || 1;
    value += change;
    if (value < 1) value = 1;
    input.value = value;
}

function submitCartForm(toolCode, button) {
    const toolCard = button.closest('.tool-card');
    const input = toolCard.querySelector('.quantity-control input');
    const quantity = parseInt(input.value.replace(/[^0-9]/g, '')) || 1;

    const form = button.parentElement.querySelector('.cart-form');
    form.querySelector('input[name="quantity"]').value = quantity;
    form.submit();
}
</script>
@endsection
