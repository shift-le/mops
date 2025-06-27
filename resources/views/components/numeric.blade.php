@if ($paginator->hasPages())
    <ul class="custom-pagination">
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="pagination-ellipsis">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination-item active"><span>{{ $page }}</span></li>
                    @else
                        <li class="pagination-item"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
    </ul>
@endif

<style>
.custom-pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding-left: 0;
    margin-top: 20px;
    flex-wrap: wrap;
}

.pagination-item {
    border: 1px solid #ccc;
    background-color: #fff;
    min-width: 34px;
    height: 34px;
    line-height: 34px;
    text-align: center;
    font-size: 14px;
}

.pagination-item a {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    color: #333;
}

.pagination-item:hover {
    background-color: #f0f0f0;
}

.pagination-item.active {
    background-color: #4a90e2;
    color: white;
    font-weight: bold;
    border-color: #4a90e2;
}

.pagination-item.active span {
    display: block;
    color: white;
}

.pagination-ellipsis {
    padding: 0 10px;
    color: #888;
    line-height: 34px;
}
</style>

