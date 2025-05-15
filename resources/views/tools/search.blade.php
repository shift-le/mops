@extends('layouts.app')

@section('content')
<div class="container">
    <div class="result-header">
            <div class="result-title-area">
                <h2 class="result-title">「{{ $hinmei->HINMEI_NAME }}」の検索結果一覧&emsp;{{ $tools->count() }}件</h2>
            </div>

        @if ($tools->isEmpty())
            <p>この品名に紐づくツールはありません。</p>
        @else

        <!-- ツール検索結果の並び替えエリア -->
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
                    <!-- サムネイル -->
            @php
                $pdfUrl = asset('storage/' . $tool->TOOL_PDF_FILE);
            @endphp

            <div class="thumbnail" onclick="loadPdf('{{ $pdfUrl }}')">
                <img src="{{ asset('storage/' . $tool->TOOL_THUM_FILE) }}" alt="サムネイル">
            </div>

                    <!-- ツール情報 -->
                    <div class="tool-info">
                        <div class="tool-code">
                            ツールコード：{{ $tool->TOOL_CODE }}
                            <button class="favorite-button {{ $tool->is_favorite ? 'active' : '' }}"
                                    onclick="toggleFavorite('{{ $tool->TOOL_CODE }}', this)">
                                <span class="icon">{{ $tool->is_favorite ? '❤️' : '♡' }}</span>
                            </button>
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
    <button onclick="updateQuantity(this, -1)">－</button>
    <button onclick="updateQuantity(this, 1)">＋</button>
</div>

    <div class="action-buttons">
        <a href="{{ route('tools.show', ['code' => $tool->TOOL_CODE]) }}" class="btn btn-success">ツール詳細</a>
        <button class="btn btn-primary"
            onclick="addToCart('{{ $tool->TOOL_CODE }}', this)">カートに入れる</button>
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

<script>
function loadPdf(pdfUrl) {
    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('pdfModal').style.display = 'block';
}

function closePdfModal() {
    document.getElementById('pdfModal').style.display = 'none';
    document.getElementById('pdfViewer').src = '';
}

function toggleFavorite(toolCode, button) {
    const isActive = button.classList.contains('active');
    const url = isActive ? '/favorite/remove' : '/favorite/add';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ tool_code: toolCode })
    }).then(response => response.json())
    .then(() => {
        button.classList.toggle('active');
        button.querySelector('.icon').textContent = isActive ? '♡' : '❤️';
    });
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

function addToCart(toolCode, button) {
    const input = button.parentElement.querySelector('input');
    const quantity = parseInt(input.value.replace(/[^0-9]/g, '')) || 1;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ tool_code: toolCode, quantity: quantity })
    }).then(response => response.json())
    .then(() => alert('カートに追加しました'));
}
</script>
@endsection


