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
@php
    $__urgencyText = $__urgency->urgency_banner_text ?? 'Les examens approchent — préparez-vous dès maintenant !';
@endphp
<style>
    #urgency-banner { position: relative; overflow: hidden; background: linear-gradient(90deg,#dc2626,#ea580c); color: #fff; padding: .65rem 0; font-weight: 700; font-size: .9rem; }
    #urgency-banner .urgency-marquee-track { display: flex; width: max-content; animation: urgency-marquee 22s linear infinite; }
    #urgency-banner .urgency-marquee-item { display: flex; align-items: center; gap: .6rem; padding-inline-end: 4rem; flex-shrink: 0; white-space: nowrap; }
    #urgency-banner .urgency-marquee-item a { color: #fff; text-decoration: underline; }
    #urgency-banner .urgency-countdown-slot { background: rgba(0,0,0,.2); padding: .15rem .6rem; border-radius: 999px; font-variant-numeric: tabular-nums; }
    #urgency-banner:hover .urgency-marquee-track { animation-play-state: paused; }
    #urgency-banner-close { position: absolute; top: 50%; right: .75rem; transform: translateY(-50%); width: 26px; height: 26px; border-radius: 50%; background: rgba(0,0,0,.18); border: none; color: #fff; font-size: 1.1rem; cursor: pointer; line-height: 1; display: flex; align-items: center; justify-content: center; z-index: 2; }
    @keyframes urgency-marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
        #urgency-banner .urgency-marquee-track { animation: none; }
    }
</style>
<div id="urgency-banner" data-target="{{ $__urgencyTargetIso }}" data-cookie="{{ $__urgencyCookieName }}">
    <div class="urgency-marquee-track">
        @for($i = 0; $i < 2; $i++)
        <span class="urgency-marquee-item" @if($i > 0) aria-hidden="true" @endif>
            <span>{{ $__urgencyText }}</span>
            <span class="urgency-countdown-slot" data-countdown-slot></span>
            @if($__urgency->urgency_banner_link)
            <a href="{{ $__urgency->urgency_banner_link }}">Voir les documents →</a>
            @endif
        </span>
        @endfor
    </div>
    <button type="button" id="urgency-banner-close" onclick="dismissUrgencyBanner()" aria-label="Fermer">&times;</button>
</div>
<script>
(function () {
    var banner = document.getElementById('urgency-banner');
    if (!banner) return;
    var target = new Date(banner.dataset.target).getTime();
    var cookieName = banner.dataset.cookie;
    var countdownSlots = banner.querySelectorAll('[data-countdown-slot]');

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
        var text = days > 0
            ? days + 'j ' + hours + 'h restants'
            : hours + 'h ' + minutes + 'min restants';
        countdownSlots.forEach(function (el) {
            el.textContent = text;
        });
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
