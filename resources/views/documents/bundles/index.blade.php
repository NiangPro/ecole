@extends('layouts.app')

@section('title', 'Packs de Documents | NiangProgrammeur')
@section('meta_description', 'Découvrez nos packs de documents à prix réduit. Économisez en achetant plusieurs documents ensemble.')

@section('content')
<div style="min-height: 100vh; padding: 2rem 1rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Hero Section -->
        <div style="text-align: center; padding: 3rem 0; margin-bottom: 3rem;">
            <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; color: #1e293b; margin-bottom: 1rem;">
                <i class="fas fa-box"></i> Packs de Documents
            </h1>
            <p style="font-size: 1.25rem; color: #64748b; max-width: 800px; margin: 0 auto;">
                Économisez en achetant plusieurs documents ensemble. Nos packs offrent des réductions exclusives !
            </p>
        </div>

        @if($bundles->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
            @foreach($bundles as $bundle)
            <div style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                <a href="{{ route('bundles.show', $bundle->slug) }}" style="text-decoration: none; color: inherit;">
                    @if($bundle->cover_image)
                    <div style="width: 100%; height: 200px; overflow: hidden;">
                        @if($bundle->cover_type === 'internal')
                            <img src="{{ asset('storage/' . $bundle->cover_image) }}" alt="{{ $bundle->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="{{ $bundle->cover_image }}" alt="{{ $bundle->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    @endif
                    <div style="padding: 1.5rem;">
                        <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #1e293b;">
                            {{ $bundle->name }}
                        </h3>
                        @if($bundle->description)
                        <p style="color: #64748b; margin-bottom: 1rem; line-height: 1.6;">
                            {{ \Illuminate\Support\Str::limit($bundle->description, 120) }}
                        </p>
                        @endif
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <div style="font-size: 1.75rem; font-weight: 900; color: #06b6d4;">
                                    {{ number_format($bundle->current_price, 0, ',', ' ') }} FCFA
                                </div>
                                @if($bundle->hasDiscount())
                                <div style="font-size: 0.875rem; color: #64748b; text-decoration: line-through;">
                                    {{ number_format($bundle->price, 0, ',', ' ') }} FCFA
                                </div>
                                @endif
                            </div>
                            @if($bundle->savings > 0)
                            <div style="background: #10b981; color: white; padding: 4px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 700;">
                                Économisez {{ number_format($bundle->savings, 0, ',', ' ') }} FCFA
                            </div>
                            @endif
                        </div>
                        <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">
                            <i class="fas fa-file"></i> {{ $bundle->items->count() }} documents inclus
                        </div>
                        <div style="padding: 12px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border-radius: 8px; text-align: center; font-weight: 700;">
                            Voir les détails <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: 3rem; display: flex; justify-content: center;">
            {{ $bundles->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 20px;">
            <i class="fas fa-box" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: #1e293b;">Aucun pack disponible</h2>
            <p style="color: #64748b;">Revenez bientôt pour découvrir nos packs exclusifs !</p>
        </div>
        @endif
    </div>
</div>
@endsection

