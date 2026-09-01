{{--
    Carte publicité vidéo YouTube — partagée entre l'accueil (hp-native-ad) et la
    sidebar des articles emplois (art-sidebar-ad). Props :
      - $ad      : App\Models\Ad avec ad_code rempli (vidéo)
      - $label   : texte du badge ("Sponsorisé", "Partenaire"...)

    Le lecteur YouTube (iframe) n'est créé qu'au clic sur le bouton play — jamais au
    chargement de la page. Contrairement à TikTok, YouTube autorise la lecture
    automatique en boucle avec le son dès lors qu'elle démarre suite à un geste de
    l'utilisateur (le clic) — la vidéo se lance donc réellement seule, en boucle.
--}}
@php $video = $ad->video_data; @endphp
@if($video)
<div class="np-youtube-ad">
    <span class="np-youtube-ad__label">{{ $label ?? 'Sponsorisé' }}</span>

    <div class="np-youtube-ad__stage" data-youtube-stage data-youtube-id="{{ $video['video_id'] }}">
        <div class="np-youtube-ad__thumb" data-youtube-thumb
             @if($video['thumbnail_url']) style="background-image:url('{{ $video['thumbnail_url'] }}')" @endif>
            <button type="button" class="np-youtube-ad__play" onclick="nAdYouTubePlay({{ $ad->id }}, this)"
                    aria-label="Lire la vidéo YouTube : {{ $ad->name }}">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </button>
            <span class="np-youtube-ad__platform">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5v-7L15.8 12l-6.2 3.5Z"/></svg>
                YouTube
            </span>
        </div>
        <div class="np-youtube-ad__embed" data-youtube-embed hidden></div>
    </div>

    <div class="np-youtube-ad__content">
        <h4 class="np-youtube-ad__title">{{ $ad->name }}</h4>
        @if($ad->description)
        <p class="np-youtube-ad__desc">{{ $ad->description }}</p>
        @endif
        @if($video['author_name'] ?? null)
        <span class="np-youtube-ad__author"><i class="fas fa-user"></i> {{ $video['author_name'] }}</span>
        @endif
        <a href="{{ $ad->link_url ?: $video['youtube_url'] }}" target="_blank" rel="noopener"
           class="np-youtube-ad__cta" onclick="nAdTrackAdClick({{ $ad->id }})">
            Voir sur YouTube
            <i class="fas fa-arrow-up-right-from-square"></i>
        </a>
    </div>
</div>
@endif
