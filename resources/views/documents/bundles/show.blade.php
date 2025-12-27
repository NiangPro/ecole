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
                        Documents inclus ({{ $bundle->items->count() }})
                    </h2>
                    <div style="display: grid; gap: 1rem;">
                        @foreach($bundle->items as $item)
                        <div style="display: flex; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 12px;">
                            @if($item->document->cover_image)
                            <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                                @if($item->document->cover_type === 'internal')
                                    <img src="{{ asset('storage/' . $item->document->cover_image) }}" alt="{{ $item->document->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ $item->document->cover_image }}" alt="{{ $item->document->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            @endif
                            <div style="flex: 1;">
                                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #1e293b;">
                                    <a href="{{ route('documents.show', $item->document->slug) }}" style="color: inherit; text-decoration: none;">
                                        {{ $item->document->title }}
                                    </a>
                                </h3>
                                <div style="font-size: 0.875rem; color: #64748b;">
                                    @if($item->document->isFree())
                                        <span style="color: #10b981; font-weight: 700;">Gratuit</span>
                                    @else
                                        {{ number_format($item->document->current_price, 0, ',', ' ') }} FCFA
                                    @endif
                                </div>
                            </div>
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

                    <form action="{{ route('documents.cart.add', $bundle->items->first()->document_id) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s;">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

