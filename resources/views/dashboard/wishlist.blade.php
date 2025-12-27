@extends('dashboard.layout')

@section('title', 'Ma Liste de Souhaits')

@section('dashboard-content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-heart"></i> Ma Liste de Souhaits
    </h1>
    <p class="page-subtitle">Vos documents favoris sauvegardés pour plus tard</p>
</div>

<div class="content-card">
    @if($wishlistItems->count() > 0)
    <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @foreach($wishlistItems as $item)
        <div class="wishlist-item-card" style="
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(6, 182, 212, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(6, 182, 212, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <a href="{{ route('documents.show', $item->document->slug) }}" style="text-decoration: none; color: inherit;">
                <div style="display: flex; gap: 1rem;">
                    @if($item->document->cover_image)
                        <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; flex-shrink: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            @if($item->document->cover_type === 'internal')
                                <img src="{{ asset('storage/' . $item->document->cover_image) }}" alt="{{ $item->document->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="{{ $item->document->cover_image }}" alt="{{ $item->document->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 12px; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-file-alt" style="font-size: 2rem; color: white;"></i>
                        </div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <h3 class="dashboard-text-primary" style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.3;">
                            {{ \Illuminate\Support\Str::limit($item->document->title, 60) }}
                        </h3>
                        @if($item->document->category)
                        <div class="dashboard-text-secondary" style="font-size: 0.875rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-folder"></i> {{ $item->document->category->name }}
                        </div>
                        @endif
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem;">
                            <div style="font-size: 1.25rem; font-weight: 800; color: #06b6d4;">
                                @if($item->document->isFree())
                                    <span style="color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 4px 12px; border-radius: 8px; font-size: 0.875rem;">Gratuit</span>
                                @else
                                    @php
                                        $currentPrice = $item->document->current_price;
                                        $originalPrice = $item->document->price;
                                    @endphp
                                    @if($item->document->discount_price && $item->document->discount_price < $originalPrice)
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="text-decoration: line-through; font-size: 0.875rem; color: #94a3b8; font-weight: 400;">{{ number_format($originalPrice, 0, ',', ' ') }} FCFA</span>
                                            <span style="color: #10b981;">{{ number_format($currentPrice, 0, ',', ' ') }} FCFA</span>
                                        </div>
                                    @else
                                        <span>{{ number_format($currentPrice, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                <form action="{{ route('wishlist.remove', $item->document->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce document de votre liste de souhaits ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger-sm" style="width: 100%; padding: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(239, 68, 68, 0.2)';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)';">
                        <i class="fas fa-trash"></i> Retirer de la wishlist
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($wishlistItems->hasPages())
    <div style="margin-top: 2rem;">
        {{ $wishlistItems->links() }}
    </div>
    @endif
    @else
    <div class="empty-state" style="text-align: center; padding: 4rem 2rem;">
        <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem; border-radius: 50%; background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(236, 72, 153, 0.1)); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-heart" style="font-size: 4rem; color: #ef4444; opacity: 0.5;"></i>
        </div>
        <h2 class="dashboard-text-primary" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Votre liste de souhaits est vide</h2>
        <p class="dashboard-text-secondary" style="margin-bottom: 2rem;">Commencez à ajouter des documents à votre liste de souhaits pour les retrouver facilement plus tard !</p>
        <a href="{{ route('documents.index') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(6, 182, 212, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <i class="fas fa-search"></i> Parcourir les documents
        </a>
    </div>
    @endif
</div>

@push('styles')
<style>
    body.dark-mode .wishlist-item-card {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.dark-mode .wishlist-item-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3) !important;
    }
</style>
@endpush
@endsection

