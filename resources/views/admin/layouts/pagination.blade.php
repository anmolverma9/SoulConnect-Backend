@if ($paginator->hasPages())
    <div class="custom-pagination">
        <div class="pagination-info">
            Showing <span class="highlight">{{ $paginator->firstItem() }}</span> to <span class="highlight">{{ $paginator->lastItem() }}</span> of <span class="highlight">{{ $paginator->total() }}</span> entries
        </div>

        <div class="pagination-pages">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">
                    <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i> Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-btn disabled" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">
                    Next <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                </a>
            @else
                <span class="page-btn disabled" aria-disabled="true">
                    Next <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                </span>
            @endif
        </div>
    </div>
@endif
