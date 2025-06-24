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
