@if ($paginator->hasPages())
    <nav class="pagination-wrapper" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="pagination-link disabled">
                        <span class="pagination-arrow">&lt;</span>
                    </span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev" aria-label="@lang('pagination.previous')">
                        <span class="pagination-arrow">&lt;</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item disabled" aria-disabled="true">
                        <span class="pagination-link disabled">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active" aria-current="page">
                                <span class="pagination-link active">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next" aria-label="@lang('pagination.next')">
                        <span class="pagination-arrow">&gt;</span>
                    </a>
                </li>
            @else
                <li class="pagination-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="pagination-link disabled">
                        <span class="pagination-arrow">&gt;</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
    
    <style>
        /* Pagination Style - Comme dans l'image */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem 0;
            padding: 1.5rem 0;
        }
        
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }
        
        .pagination-item {
            margin: 0;
        }
        
        .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            color: #424242;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .pagination-link:hover:not(.disabled):not(.active) {
            background: #eeeeee;
            border-color: #bdbdbd;
        }
        
        .pagination-link.active {
            background: #2196f3;
            border-color: #2196f3;
            color: #ffffff;
            font-weight: 600;
        }
        
        .pagination-link.disabled {
            background: #f5f5f5;
            border-color: #e0e0e0;
            color: #9e9e9e;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagination-link.disabled:hover {
            background: #f5f5f5;
            border-color: #e0e0e0;
        }
        
        .pagination-arrow {
            font-size: 16px;
            font-weight: 600;
            color: inherit;
        }
        
        /* Dark Mode */
        body.dark-mode .pagination-link {
            background: rgba(30, 30, 30, 0.8);
            border-color: rgba(100, 100, 100, 0.3);
            color: rgba(255, 255, 255, 0.8);
        }
        
        body.dark-mode .pagination-link:hover:not(.disabled):not(.active) {
            background: rgba(50, 50, 50, 0.9);
            border-color: rgba(120, 120, 120, 0.4);
        }
        
        body.dark-mode .pagination-link.active {
            background: #2196f3;
            border-color: #2196f3;
            color: #ffffff;
        }
        
        body.dark-mode .pagination-link.disabled {
            background: rgba(30, 30, 30, 0.5);
            border-color: rgba(80, 80, 80, 0.2);
            color: rgba(150, 150, 150, 0.6);
        }
        
        body.dark-mode .pagination-link.disabled:hover {
            background: rgba(30, 30, 30, 0.5);
            border-color: rgba(80, 80, 80, 0.2);
        }
        
        /* Responsive Mobile */
        @media (max-width: 768px) {
            .pagination {
                gap: 0.375rem;
            }
            
            .pagination-link {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: 13px;
            }
            
            .pagination-arrow {
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .pagination-link {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
                font-size: 12px;
            }
        }
    </style>
@endif
