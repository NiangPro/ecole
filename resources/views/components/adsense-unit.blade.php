@php
    // Récupérer les paramètres AdSense
    $adsenseSettings = \App\Models\AdSenseSetting::first();
    
    // Extraire l'ID client du code AdSense
    $clientId = null;
    if ($adsenseSettings && $adsenseSettings->adsense_code) {
        if (preg_match('/ca-pub-([0-9]+)/', $adsenseSettings->adsense_code, $matches)) {
            $clientId = 'ca-pub-' . $matches[1];
        }
    }
    
    // Si un unitId est fourni, récupérer l'unité depuis la base de données
    $unit = null;
    if (isset($unitId)) {
        $unit = \App\Models\AdSenseUnit::find($unitId);
    }
    
    // Si une position est fournie, récupérer les unités pour cette position
    if (!$unit && isset($position)) {
        $location = $location ?? null;
        $units = \App\Models\AdSenseUnit::getUnitsForPosition($position, $location);
        $unit = $units->first(); // Prendre la première unité active
    }
    
    // Utiliser les paramètres de l'unité si disponible, sinon utiliser les paramètres fournis
    if ($unit) {
        $adSlot = $unit->ad_slot;
        $adFormat = $unit->ad_format;
        $responsive = $unit->responsive ? 'true' : 'false';
        $containerClass = $containerClass ?? 'adsense-container';
        $containerStyle = $containerStyle ?? 'margin: 20px 0; text-align: center; min-height: 250px;';
    } else {
        // Utiliser les paramètres fournis directement
        $clientId = $clientId ?? ($clientIdParam ?? null);
        $adSlot = $adSlot ?? null;
        $adFormat = $adFormat ?? 'auto';
        $responsive = $responsive ?? 'true';
        $containerClass = $containerClass ?? 'adsense-container';
        $containerStyle = $containerStyle ?? 'margin: 20px 0; text-align: center; min-height: 250px;';
    }
    
    $adStyle = $adStyle ?? 'display:block';
@endphp

@if($clientId && $adSlot)
<div class="{{ $containerClass }}" style="{{ $containerStyle }}">
    @if($unit && $unit->custom_code)
        {!! $unit->custom_code !!}
    @else
    <ins class="adsbygoogle"
         style="{{ $adStyle }}"
         data-ad-client="{{ $clientId }}"
         data-ad-slot="{{ $adSlot }}"
         data-ad-format="{{ $adFormat }}"
         data-full-width-responsive="{{ $responsive }}"></ins>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    @endif
</div>
@elseif($clientId && !$adSlot)
{{-- Auto Ads - Le code AdSense gère automatiquement --}}
@else
{{-- AdSense non configuré --}}
@endif

