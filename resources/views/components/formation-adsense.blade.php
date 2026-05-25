@php
    $ads = isset($ads) ? $ads : [];
    $position = $position ?? 'content';
    $activeAds = is_object($ads) ? $ads->filter(fn($ad) => $ad->adsenseUnit && $ad->adsenseUnit->status === 'active') : collect();
@endphp

@if($activeAds->isNotEmpty())
<div class="formation-adsense-section" style="margin: 30px 0; padding: 20px 0; border-top: 1px solid rgba(6, 182, 212, 0.2); border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
    @foreach($activeAds as $ad)
        <div class="formation-ad-container" style="margin: 20px 0; text-align: center;">
            @include('components.adsense-unit', [
                'unitId' => $ad->adsenseUnit->id,
                'containerStyle' => 'margin: 20px auto; text-align: center; max-width: 100%;'
            ])
        </div>
    @endforeach
</div>
@endif

