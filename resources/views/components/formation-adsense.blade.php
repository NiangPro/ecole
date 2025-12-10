@php
    // Récupérer les annonces pour cette formation et cette position
    $ads = isset($ads) ? $ads : [];
    $position = $position ?? 'content';
@endphp

@if($ads && $ads->count() > 0)
<div class="formation-adsense-section" style="margin: 30px 0; padding: 20px 0; border-top: 1px solid rgba(6, 182, 212, 0.2); border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
    @foreach($ads as $ad)
        @if($ad->adsenseUnit && $ad->adsenseUnit->status === 'active')
        <div class="formation-ad-container" style="margin: 20px 0; text-align: center; min-height: 250px;">
            @include('components.adsense-unit', [
                'unitId' => $ad->adsenseUnit->id,
                'containerStyle' => 'margin: 20px auto; text-align: center; min-height: 250px; max-width: 100%;'
            ])
        </div>
        @endif
    @endforeach
</div>
@endif

