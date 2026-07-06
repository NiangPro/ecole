{{--
    Encadré Natif E-book NiangProgrammeur (Tâche 5 — Tunnel de Conversion)
    Usage dans un article : <x-ebook-promo :type="$contentType" />

    Alterne entre :
      • "Le Décodeur de Carrière" (pour les silos Carrière & Concours)
      • "Money API"              (pour le silo Éducation Tech, fallback générique)
--}}
@props(['type' => 'offre'])

@php
    $isCareer = in_array($type, ['offre', 'concours', 'bourses', 'administratif']);
    if ($isCareer) {
        $ebookTitle    = 'Le Décodeur de Carrière';
        $ebookSubtitle = 'Le guide complet pour décrocher un emploi au Sénégal';
        $ebookIcon     = 'fas fa-briefcase';
        $ebookUrl      = route('documents.show', 'le-decodeur-de-carriere-guide-ultime-pour-decrocher-un-emploi');
        $ebookBadge    = 'Emploi & Recrutement';
        $ebookDesc     = 'Stratégies de CV, préparation aux entretiens, négociation salariale et réseau professionnel — tout ce que l\'école ne vous a jamais appris.';
        $ebookCta      = 'Obtenir le guide →';
    } else {
        $ebookTitle    = 'Money API';
        $ebookSubtitle = 'Monétisez vos compétences tech dès aujourd\'hui';
        $ebookIcon     = 'fas fa-rocket';
        $ebookUrl      = route('documents.index');
        $ebookBadge    = 'Tech & Revenus';
        $ebookDesc     = 'De la ligne de code au revenu passif : freelance, SaaS, APIs et startups — le playbook des développeurs africains qui gagnent bien.';
        $ebookCta      = 'Découvrir Money API →';
    }
@endphp

<aside class="np-ebook-promo" aria-label="Ressource recommandée par NiangProgrammeur">
<style>
.np-ebook-promo{
    position:relative;
    margin:2rem 0;
    padding:1.6rem 1.75rem;
    background:linear-gradient(135deg,#065f46 0%,#0891b2 100%);
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 32px rgba(6,182,212,.22);
}
.np-ebook-promo::before{
    content:'';
    position:absolute;inset:0;
    background:radial-gradient(ellipse 70% 70% at 90% 10%,rgba(255,255,255,.08) 0%,transparent 60%);
    pointer-events:none;
}
.np-ebook-badge{
    display:inline-flex;align-items:center;gap:.4rem;
    padding:.25rem .8rem;
    background:rgba(255,255,255,.18);
    border:1px solid rgba(255,255,255,.3);
    border-radius:999px;
    font-size:.65rem;font-weight:800;
    letter-spacing:.08em;text-transform:uppercase;
    color:rgba(255,255,255,.95);
    margin-bottom:.85rem;
}
.np-ebook-header{
    display:flex;align-items:flex-start;gap:1rem;
    margin-bottom:.85rem;
}
.np-ebook-icon{
    flex-shrink:0;
    width:48px;height:48px;
    background:rgba(255,255,255,.15);
    border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.3rem;color:#fff;
}
.np-ebook-titles{flex:1;}
.np-ebook-title{
    font-size:1.2rem;font-weight:900;
    color:#fff;margin:0 0 .2rem;
    line-height:1.2;
}
.np-ebook-subtitle{
    font-size:.8rem;color:rgba(255,255,255,.8);
    margin:0;font-weight:500;
}
.np-ebook-desc{
    font-size:.875rem;line-height:1.65;
    color:rgba(255,255,255,.88);
    margin-bottom:1.1rem;
}
.np-ebook-cta{
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.65rem 1.4rem;
    background:#fff;
    color:#065f46;
    font-size:.875rem;font-weight:800;
    border-radius:999px;
    text-decoration:none;
    transition:transform .2s,box-shadow .2s;
    box-shadow:0 4px 14px rgba(0,0,0,.18);
}
.np-ebook-cta:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 24px rgba(0,0,0,.25);
    color:#065f46;text-decoration:none;
}
.np-ebook-mention{
    font-size:.7rem;color:rgba(255,255,255,.55);
    margin-top:.7rem;
}
</style>
    <div class="np-ebook-badge">
        <i class="{{ $ebookIcon }}"></i> {{ $ebookBadge }}
    </div>
    <div class="np-ebook-header">
        <div class="np-ebook-icon"><i class="{{ $ebookIcon }}"></i></div>
        <div class="np-ebook-titles">
            <p class="np-ebook-title">{{ $ebookTitle }}</p>
            <p class="np-ebook-subtitle">{{ $ebookSubtitle }}</p>
        </div>
    </div>
    <p class="np-ebook-desc">{{ $ebookDesc }}</p>
    <a href="{{ $ebookUrl }}" class="np-ebook-cta" rel="noopener">
        {{ $ebookCta }}
    </a>
    <p class="np-ebook-mention">Par NiangProgrammeur · Ressource exclusive pour la communauté</p>
</aside>
