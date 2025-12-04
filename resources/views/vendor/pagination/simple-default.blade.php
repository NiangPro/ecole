@if ($paginator->hasPages())
    <nav class="modern-pagination-wrapper" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="modern-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="modern-pagination-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="modern-pagination-link disabled">
                        <i class="fas fa-chevron-left"></i>
                        <span class="pagination-text">{{ trans('app.pagination.previous') ?? 'Précédent' }}</span>
                    </span>
                </li>
            @else
                <li class="modern-pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="modern-pagination-link" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="fas fa-chevron-left"></i>
                        <span class="pagination-text">{{ trans('app.pagination.previous') ?? 'Précédent' }}</span>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="modern-pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="modern-pagination-link" rel="next" aria-label="@lang('pagination.next')">
                        <span class="pagination-text">{{ trans('app.pagination.next') ?? 'Suivant' }}</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="modern-pagination-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="modern-pagination-link disabled">
                        <span class="pagination-text">{{ trans('app.pagination.next') ?? 'Suivant' }}</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
    
    <style>
        /* Pagination Moderne */
        .modern-pagination-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
            padding: 1.5rem 0;
        }
        
        .modern-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }
        
        .modern-pagination-item {
            margin: 0;
        }
        
        .modern-pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 44px;
            height: 44px;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(6, 182, 212, 0.2);
            border-radius: 12px;
            color: rgba(30, 41, 59, 0.8);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .modern-pagination-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(6, 182, 212, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }
        
        .modern-pagination-link:hover::before {
            width: 200%;
            height: 200%;
        }
        
        .modern-pagination-link:hover {
            background: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.4);
            color: #06b6d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        }
        
        .modern-pagination-link.disabled {
            background: rgba(148, 163, 184, 0.1);
            border-color: rgba(148, 163, 184, 0.2);
            color: rgba(148, 163, 184, 0.5);
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .modern-pagination-link.disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .modern-pagination-link i {
            font-size: 0.85rem;
        }
        
        .pagination-text {
            font-size: 0.9rem;
        }
        
        /* Dark Mode */
        body.dark-mode .modern-pagination-link {
            background: rgba(15, 23, 42, 0.8);
            border-color: rgba(6, 182, 212, 0.3);
            color: rgba(255, 255, 255, 0.9);
        }
        
        body.dark-mode .modern-pagination-link:hover {
            background: rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.5);
            color: #06b6d4;
        }
        
        body.dark-mode .modern-pagination-link.disabled {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(148, 163, 184, 0.2);
            color: rgba(148, 163, 184, 0.5);
        }
        
        /* Responsive Mobile */
        @media (max-width: 768px) {
            .modern-pagination {
                gap: 0.375rem;
            }
            
            .modern-pagination-link {
                min-width: 40px;
                height: 40px;
                padding: 0.625rem 0.75rem;
                font-size: 0.85rem;
            }
            
            .pagination-text {
                display: none;
            }
            
            .modern-pagination-link i {
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .modern-pagination-link {
                min-width: 36px;
                height: 36px;
                padding: 0.5rem;
            }
        }
    </style>
@endif
