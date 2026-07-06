@extends('layouts.app')

@section('title', $bundle->name . ' - Packs | NiangProgrammeur')
@section('meta_description', $bundle->description ?? 'Découvrez ce pack de documents avec des réductions exclusives.')

@section('content')
<div style="min-height: 100vh; padding: 2rem 1rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 8px 30px rgba(0,0,0,0.1);">
            <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 1rem; color: #1e293b;">
                {{ $bundle->name }}
            </h1>
            
            @if($bundle->description)
            <p style="font-size: 1.1rem; color: #64748b; margin-bottom: 2rem; line-height: 1.6;">
                {{ $bundle->description }}
            </p>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; margin-bottom: 3rem;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b;">
                        Contenu du pack ({{ $bundle->items->count() }})
                    </h2>
                    <div style="display: grid; gap: 1rem;">
                        @foreach($bundle->items as $item)
                        @php $itemable = $item->itemable; @endphp
                        @continue(!$itemable)
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 12px;">
                            @if($item->item_type === \App\Models\Document::class)
                                @if($itemable->cover_image)
                                <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                                    @if($itemable->cover_type === 'internal')
                                        <img src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $itemable->id]) }}" alt="{{ $itemable->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ $itemable->cover_image }}" alt="{{ $itemable->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                @endif
                                <div style="flex: 1;">
                                    <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #1e293b;">
                                        <a href="{{ route('documents.show', $itemable->slug) }}" style="color: inherit; text-decoration: none;">
                                            {{ $itemable->title }}
                                        </a>
                                    </h3>
                                    <div style="font-size: 0.875rem; color: #64748b;">
                                        @if($itemable->isFree())
                                            <span style="color: #10b981; font-weight: 700;">Gratuit</span>
                                        @else
                                            {{ number_format($itemable->current_price, 0, ',', ' ') }} FCFA
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div style="width: 80px; height: 80px; border-radius: 8px; flex-shrink: 0; background: linear-gradient(135deg, #a78bfa, #7c3aed); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-graduation-cap" style="color: white; font-size: 1.75rem;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #1e293b;">
                                        <a href="{{ route('epreuves.show', $itemable->slug) }}" style="color: inherit; text-decoration: none;">
                                            {{ $itemable->title }}
                                        </a>
                                    </h3>
                                    <div style="font-size: 0.875rem; color: #64748b;">
                                        @if($itemable->isFree())
                                            <span style="color: #10b981; font-weight: 700;">Gratuit</span>
                                        @else
                                            {{ number_format($itemable->price, 0, ',', ' ') }} FCFA
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 2rem; border-radius: 16px; height: fit-content; position: sticky; top: 100px;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b;">Résumé</h3>
                    
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #64748b;">Prix total individuel :</span>
                            <span style="font-weight: 700; color: #64748b; text-decoration: line-through;">
                                {{ number_format($bundle->total_individual_price, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #64748b;">Prix du pack :</span>
                            <span style="font-weight: 700; color: #06b6d4; font-size: 1.25rem;">
                                {{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if($bundle->savings > 0)
                        <div style="display: flex; justify-content: space-between; padding-top: 0.5rem; border-top: 1px solid #e2e8f0;">
                            <span style="font-weight: 700; color: #10b981;">Vous économisez :</span>
                            <span style="font-weight: 800; color: #10b981; font-size: 1.1rem;">
                                {{ number_format($bundle->savings, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @endif
                    </div>

                    @php $hasEpreuveItem = $bundle->items->contains('item_type', \App\Models\Epreuve::class); @endphp
                    @if($hasEpreuveItem)
                        <div style="padding: 1rem; background: #fef3c7; border-radius: 12px; color: #92400e; font-size: 0.9rem; text-align: center;">
                            <i class="fas fa-info-circle"></i>
                            Ce pack contient des épreuves : l'achat groupé n'est pas encore disponible pour ce type de contenu.
                        </div>
                    @else
                    <form action="{{ route('bundles.add-to-cart', $bundle->slug) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s;">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

