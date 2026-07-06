@php
    $__urgency = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, fn() => \App\Models\SiteSetting::first());
    $__urgencyActive = $__urgency
        && $__urgency->urgency_banner_enabled
        && $__urgency->urgency_banner_target_date
        && \Illuminate\Support\Carbon::parse($__urgency->urgency_banner_target_date)->isFuture();
    $__urgencyTargetIso = $__urgencyActive ? \Illuminate\Support\Carbon::parse($__urgency->urgency_banner_target_date)->toIso8601String() : null;
    // Décision de dismissal côté serveur (cookie), pas côté client (localStorage) :
    // le bandeau est ainsi rendu déjà dans son état final dès le premier octet HTML,
    // sans bascule display:none → display:block après coup (source de CLS en conditions réelles).
    $__urgencyCookieName = $__urgencyTargetIso ? 'urgency_banner_dismissed_' . md5($__urgencyTargetIso) : null;
    $__urgencyDismissed = $__urgencyCookieName && request()->cookie($__urgencyCookieName);
@endphp
@if($__urgencyActive && !$__urgencyDismissed)
<div id="urgency-banner" data-target="{{ $__urgencyTargetIso }}" data-cookie="{{ $__urgencyCookieName }}" style="position:relative;z-index:1000;background:linear-gradient(90deg,#dc2626,#ea580c);color:#fff;padding:.6rem 1rem;text-align:center;font-weight:700;font-size:.9rem;">
    <div style="max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:.6rem;">
        <span>{{ $__urgency->urgency_banner_text ?? 'Les examens approchent — préparez-vous dès maintenant !' }}</span>
        <span id="urgency-countdown" style="background:rgba(0,0,0,.2);padding:.2rem .6rem;border-radius:999px;font-variant-numeric:tabular-nums;"></span>
        @if($__urgency->urgency_banner_link)
        <a href="{{ $__urgency->urgency_banner_link }}" style="color:#fff;text-decoration:underline;">Voir les documents →</a>
        @endif
    </div>
    <button onclick="dismissUrgencyBanner()" aria-label="Fermer" style="position:absolute;top:50%;right:.75rem;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:1.1rem;cursor:pointer;line-height:1;">&times;</button>
</div>
<script>
(function () {
    var banner = document.getElementById('urgency-banner');
    if (!banner) return;
    var target = new Date(banner.dataset.target).getTime();
    var cookieName = banner.dataset.cookie;

    var countdownEl = document.getElementById('urgency-countdown');
    function tick() {
        var diff = target - Date.now();
        if (diff <= 0) {
            banner.remove();
            clearInterval(timer);
            return;
        }
        var days = Math.floor(diff / 86400000);
        var hours = Math.floor((diff % 86400000) / 3600000);
        var minutes = Math.floor((diff % 3600000) / 60000);
        countdownEl.textContent = days > 0
            ? days + 'j ' + hours + 'h restants'
            : hours + 'h ' + minutes + 'min restants';
    }
    tick();
    var timer = setInterval(tick, 60000);

    window.dismissUrgencyBanner = function () {
        document.cookie = cookieName + '=1; max-age=' + (60 * 60 * 24 * 90) + '; path=/; SameSite=Lax';
        banner.remove();
        clearInterval(timer);
    };
})();
</script>
@endif
