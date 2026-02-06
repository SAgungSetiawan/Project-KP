@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $showPages = 5;
        $half = floor($showPages / 2);
        
        $startPage = max(1, $currentPage - $half);
        $endPage = min($lastPage, $currentPage + $half);
        
        if ($endPage - $startPage < ($showPages - 1)) {
            if ($startPage == 1) {
                $endPage = min($lastPage, $startPage + ($showPages - 1));
            } else {
                $startPage = max(1, $endPage - ($showPages - 1));
            }
        }
    @endphp

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link text-secondary">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link text-primary" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- First Page --}}
            @if ($startPage > 1)
                <li class="page-item">
                    <a class="page-link text-primary" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($startPage > 2)
                    <li class="page-item disabled">
                        <span class="page-link text-secondary">...</span>
                    </li>
                @endif
            @endif

            {{-- Pagination Numbers --}}
            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link bg-primary border-primary text-white">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link text-primary" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($endPage < $lastPage)
                @if ($endPage < $lastPage - 1)
                    <li class="page-item disabled">
                        <span class="page-link text-secondary">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link text-primary" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link text-primary" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link text-secondary">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
        
        {{-- Info halaman --}}
        <div class="text-center text-muted small mt-2">
            Menampilkan <strong>{{ $paginator->firstItem() }}</strong> - 
            <strong>{{ $paginator->lastItem() }}</strong> dari 
            <strong>{{ $paginator->total() }}</strong> klien
        </div>
    </nav>
@endif