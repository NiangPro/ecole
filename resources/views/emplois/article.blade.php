@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/emplois.css')
@endpush

@section('title', $article->meta_title ?: ($article->title . ' | NiangProgrammeur'))

@php
    // Construire une meta description de qualité (120-160 chars)
    // ?: couvre null ET chaîne vide, contrairement à ??
    $metaDesc = $article->meta_description ?: $article->excerpt ?: null;
    if (empty($metaDesc)) {
        // Fallback : premier 160 chars du contenu, nettoyé et coupé au dernier espace
        $rawText  = preg_replace('/\s+/', ' ', trim(strip_tags($article->content ?? '')));
        if (strlen($rawText) > 155) {
            $cut      = substr($rawText, 0, 152);
            $lastSpace = strrpos($cut, ' ');
            $metaDesc = ($lastSpace > 80 ? substr($cut, 0, $lastSpace) : $cut) . '…';
        } else {
            $metaDesc = $rawText;
        }
    }
@endphp
@section('meta_description', $metaDesc)

@php
    // Générer l'URL absolue de l'image pour les réseaux sociaux
    // Déterminer l'URL de base
    $baseUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
        ? 'https://www.niangprogrammeur.com' 
        : config('app.url');
    
    // Vérifier si l'article a une image
    $hasCoverImage = !empty($article->cover_image) && trim($article->cover_image) !== '';
    
    if ($hasCoverImage) {
        if ($article->cover_type === 'internal') {
            // Image interne : construire l'URL absolue
            // Storage::url() retourne '/storage/path/to/image.jpg'
            $storagePath = \Illuminate\Support\Facades\Storage::url($article->cover_image);
            // S'assurer que le chemin commence par /storage/
            if (!str_starts_with($storagePath, '/')) {
                $storagePath = '/' . $storagePath;
            }
            // Construire l'URL absolue
            $articleImage = rtrim($baseUrl, '/') . $storagePath;
        } else {
            // Image externe : s'assurer que c'est une URL absolue
            $externalImage = trim($article->cover_image);

            if (preg_match('/^https?:\/\//i', $externalImage)) {
                // Déjà une URL absolue
            } elseif (str_starts_with($externalImage, '//')) {
                // URL protocol-relative
                $externalImage = 'https:' . $externalImage;
            } elseif (preg_match('/^[a-z0-9.-]+\.[a-z]{2,}(\/|$)/i', $externalImage)) {
                // Ressemble à un vrai nom de domaine (ex. "cdn.example.com/img.jpg")
                $externalImage = 'https://' . ltrim($externalImage, '/');
            } else {
                // Chemin relatif au site (ex. "images/articles/xxx.jpg") : préfixer par le domaine
                $externalImage = rtrim($baseUrl, '/') . '/' . ltrim($externalImage, '/');
            }

            $articleImage = $externalImage;
        }
    } else {
        // Pas d'image : utiliser le logo du site avec URL absolue
        $articleImage = rtrim($baseUrl, '/') . '/images/logo.png';
    }
    
    // S'assurer que l'URL est absolue (commence par http:// ou https://)
    if (!preg_match('/^https?:\/\//i', $articleImage)) {
        $articleImage = rtrim($baseUrl, '/') . '/' . ltrim($articleImage, '/');
    }
    
    // Nettoyer l'URL (enlever les espaces, etc.)
    $articleImage = trim($articleImage);
@endphp

@php
    // Générer l'URL absolue de l'article pour Facebook
    $articleUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
        ? 'https://www.niangprogrammeur.com/emplois/article/' . $article->slug
        : url(route('emplois.article', $article->slug));

    $breadcrumbs = [
        ['name' => 'Accueil', 'url' => url('/')],
        ['name' => trans('app.nav.jobs'), 'url' => route('emplois')],
        ['name' => $article->title, 'url' => $articleUrl],
    ];
@endphp

@section('canonical', $articleUrl)

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $articleUrl }}">
    <meta property="og:title" content="{{ htmlspecialchars($article->meta_title ?? $article->title, ENT_QUOTES, 'UTF-8') }}">
    <meta property="og:description" content="{{ htmlspecialchars($article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 160), ENT_QUOTES, 'UTF-8') }}">
    <meta property="og:image" content="{{ $articleImage }}">
    <meta property="og:image:secure_url" content="{{ $articleImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') }}">
    <meta property="og:site_name" content="NiangProgrammeur">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $articleUrl }}">
    <meta name="twitter:title" content="{{ htmlspecialchars($article->meta_title ?? $article->title, ENT_QUOTES, 'UTF-8') }}">
    <meta name="twitter:description" content="{{ htmlspecialchars($article->meta_description ?? $article->excerpt ?? substr(strip_tags($article->content), 0, 160), ENT_QUOTES, 'UTF-8') }}">
    <meta name="twitter:image" content="{{ $articleImage }}">
    <meta name="twitter:image:alt" content="{{ htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') }}">
    
    <!-- Meta Tags SEO -->
    <meta name="keywords" content="{{ $article->meta_keywords ? implode(', ', is_array($article->meta_keywords) ? $article->meta_keywords : json_decode($article->meta_keywords, true) ?? []) : '' }}">
    <meta name="author" content="NiangProgrammeur">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    
    <!-- Schema.org JobPosting (offres d'emploi uniquement) -->
    @php
        $jldFlags = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE;
        $jobCategorySlugs = ['offres-emploi', 'candidature-spontanee', 'opportunites-professionnelles'];
        $isJobPosting = $article->category && in_array($article->category->slug, $jobCategorySlugs, true);
        if ($isJobPosting) {
            // Champs localisation : fallback sur Dakar (les offres ne stockent pas ces données)
            $jldStreetAddress  = $article->street_address ?? $article->location ?? $article->ville ?? 'Dakar';
            $jldAddressLocality = $article->ville ?? $article->location ?? 'Dakar';
            $jldAddressRegion  = $article->region ?? $article->ville ?? 'Dakar';
            $jldPostalCode     = $article->postal_code ?? '10700';

            $jldData = [
                '@context'           => 'https://schema.org',
                '@type'              => 'JobPosting',
                'title'              => $article->title ?? '',
                'description'        => strip_tags($article->excerpt ?? substr(strip_tags($article->content ?? ''), 0, 500)),
                'datePosted'         => ($article->published_at ?? $article->created_at)?->toIso8601String() ?? now()->toIso8601String(),
                'validThrough'       => ($article->published_at ?? $article->created_at)?->addMonths(2)->toIso8601String() ?? now()->addMonths(2)->toIso8601String(),
                'hiringOrganization' => [
                    '@type'  => 'Organization',
                    'name'   => 'Recruteur — via NiangProgrammeur',
                    'sameAs' => 'https://www.niangprogrammeur.com',
                ],
                'jobLocation' => [
                    '@type'   => 'Place',
                    'address' => [
                        '@type'           => 'PostalAddress',
                        'streetAddress'   => $jldStreetAddress,
                        'addressLocality' => $jldAddressLocality,
                        'addressRegion'   => $jldAddressRegion,
                        'postalCode'      => $jldPostalCode,
                        'addressCountry'  => 'SN',
                    ],
                ],
                'employmentType'                => 'FULL_TIME',
                'applicantLocationRequirements' => ['@type' => 'Country', 'name' => 'Sénégal'],
                'url'                           => $articleUrl,
            ];

            // baseSalary omis volontairement : aucune donnée salariale n'est stockée en base.
            // Google accepte son absence (champ recommandé, non obligatoire).
        }
    @endphp
    @if($isJobPosting)
    <script type="application/ld+json">{!! json_encode($jldData, $jldFlags) !!}</script>
    @endif

    <!-- Article Meta -->
    <meta property="article:published_time" content="{{ ($article->published_at ?? $article->created_at)?->toIso8601String() ?? now()->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $article->updated_at?->toIso8601String() ?? now()->toIso8601String() }}">
    <meta property="article:section" content="{{ $article->category->name ?? 'Emploi' }}">
    <meta property="article:author" content="NiangProgrammeur">
    @if($article->category)
    <meta property="article:tag" content="{{ $article->category->name }}">
    @endif

    @if(!empty($articleFaqs))
    @php
        // Les clés '@context'/'@type' sont construites par concaténation plutôt
        // qu'écrites en toutes lettres : un token "@context" littéral dans ce
        // fichier .blade.php est parfois capturé par le compilateur de directives
        // Blade (même à l'intérieur d'un tableau PHP), ce qui corrompait la clé
        // "@context" en code PHP compilé fuité dans le JSON-LD produit.
        $ldContextKey = '@' . 'context';
        $ldTypeKey = '@' . 'type';
        $faqSchema = [
            $ldContextKey => 'https://schema.org',
            $ldTypeKey    => 'FAQPage',
            'mainEntity'  => array_map(fn($faq) => [
                $ldTypeKey       => 'Question',
                'name'           => $faq['question'],
                'acceptedAnswer' => [$ldTypeKey => 'Answer', 'text' => $faq['answer']],
            ], $articleFaqs),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endif
@endpush

@if($article->cover_image)
@push('preload_images')
<link rel="preload" as="image" href="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}" fetchpriority="high">
@endpush
@endif

@section('styles')
<style>
/* ═══════════════════════════════════════════
   ARTICLE — Ultra-Modern 2026
   ═══════════════════════════════════════════ */
:root {
  --c:   #06b6d4;
  --cd:  #0891b2;
  --ct:  #14b8a6;
  --r:   20px;
  --rs:  12px;
  --tr:  all .3s cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { box-sizing: border-box; }

body:not(.dark-mode) { background: #f8fafc !important; }
body.dark-mode        { background: #020617 !important; }

/* ── Reading progress bar ── */
#art-progress {
  position: fixed; top: 0; left: 0;
  width: 0%; height: 3px;
  background: linear-gradient(90deg, var(--c), var(--ct));
  z-index: 9999;
  transition: width .1s linear;
  border-radius: 0 2px 2px 0;
}

/* ── Navbar clearance ── */
.art-hero, .art-hero-none {
  margin-block-start: var(--spacing-navbar, 76px) !important;
}

/* ── Hero with image ── */
.art-hero {
  position: relative;
  height: 68vh; min-height: 520px; max-height: 720px;
  overflow: hidden;
  display: flex; align-items: flex-end;
}
.art-hero-bg {
  position: absolute; inset: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 9s ease;
}
.art-hero:hover .art-hero-bg { transform: scale(1.06); }

.art-hero-veil {
  position: absolute; inset: 0;
  background: linear-gradient(to bottom,
    rgba(2,6,23,.05) 0%,
    rgba(2,6,23,.35) 38%,
    rgba(2,6,23,.86) 75%,
    rgba(2,6,23,.97) 100%);
}
body:not(.dark-mode) .art-hero-veil {
  background: linear-gradient(to bottom,
    rgba(15,23,42,.05) 0%,
    rgba(15,23,42,.32) 38%,
    rgba(15,23,42,.82) 75%,
    rgba(15,23,42,.95) 100%);
}

/* subtle cyan glow at bottom */
.art-hero-veil::after {
  content: '';
  position: absolute; bottom: 0; left: 0; right: 0; height: 45%;
  background: radial-gradient(ellipse 60% 80% at 25% 100%, rgba(6,182,212,.18) 0%, transparent 60%);
}

.art-hero-inner {
  position: relative; z-index: 2;
  width: 100%; max-width: 1240px;
  margin: 0 auto; padding: 0 28px 64px;
}

/* ── Hero fallback (no image) ── */
.art-hero-none {
  position: relative; min-height: 480px;
  display: flex; align-items: center;
  background: linear-gradient(140deg, #020617 0%, #0f172a 45%, #020617 100%);
  overflow: hidden;
}
.art-hero-none::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 55% 55% at 12% 50%, rgba(6,182,212,.22) 0%, transparent 55%),
    radial-gradient(ellipse 40% 40% at 88% 75%, rgba(20,184,166,.14) 0%, transparent 55%);
}
.art-hero-none-inner {
  position: relative; z-index: 1;
  width: 100%; max-width: 1240px;
  margin: 0 auto; padding: 80px 28px;
}

/* Favorite button (hero corner) */
.art-fav-wrap {
  position: absolute; top: 24px; right: 24px; z-index: 6;
}
.art-fav-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 22px;
  background: rgba(255,255,255,.11);
  backdrop-filter: blur(20px);
  border: 1.5px solid rgba(255,255,255,.25);
  border-radius: 100px;
  color: #fff; font-size: .85rem; font-weight: 600;
  cursor: pointer; transition: var(--tr);
}
.art-fav-btn:hover {
  background: rgba(255,255,255,.22);
  border-color: rgba(255,255,255,.45);
  transform: translateY(-2px);
}

/* Badge catégorie */
.art-badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 16px;
  background: rgba(6,182,212,.88);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,.22);
  border-radius: 100px;
  font-size: .72rem; font-weight: 700;
  letter-spacing: .08em; text-transform: uppercase;
  color: #fff; margin-bottom: 18px;
}

/* Font Awesome se charge en asynchrone : avant son arrivée, les <i> du hero sont
   des glyphes vides (largeur 0). Réserver leur largeur évite le reflow du badge et
   des chips au chargement de FA — source de CLS mesurée sur .art-badge / .art-hero-inner. */
.art-badge i, .art-chip i, .art-fav-btn i, .art-meta-row i {
  display: inline-block;
  width: 1em;
  text-align: center;
  font-style: normal;
}

/* Titre hero */
.art-hero-title {
  font-size: clamp(1.9rem, 4.2vw, 3.6rem);
  font-weight: 900; color: #fff;
  line-height: 1.12; letter-spacing: -.025em;
  margin-bottom: 26px; max-width: 820px;
  text-shadow: 0 2px 32px rgba(0,0,0,.28);
}

/* Meta chips */
.art-meta-row {
  display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.art-chip {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 7px 15px;
  background: rgba(255,255,255,.1);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255,255,255,.18);
  border-radius: 100px;
  font-size: .8rem; font-weight: 500;
  color: rgba(255,255,255,.9); white-space: nowrap;
}
.art-chip i { font-size: .7rem; opacity: .8; }

/* ── Page wrapper ── */
.art-page {
  max-width: 1240px; margin: 0 auto;
  padding: 52px 28px 88px;
}
.art-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 52px; align-items: start;
}

/* ── Back link ── */
.art-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 9px 18px;
  border: 1.5px solid rgba(6,182,212,.3);
  border-radius: var(--rs);
  color: var(--cd); font-size: .875rem; font-weight: 600;
  text-decoration: none; transition: var(--tr);
  margin-bottom: 28px;
}
.art-back:hover {
  background: rgba(6,182,212,.07);
  border-color: var(--c);
  transform: translateX(-4px);
}
body.dark-mode .art-back { color: var(--c); }

/* ── Important notice ── */
.art-notice {
  display: flex; gap: 16px;
  padding: 20px 22px;
  background: rgba(239,68,68,.06);
  border: 1px solid rgba(239,68,68,.22);
  border-left: 4px solid #ef4444;
  border-radius: var(--rs);
  margin-bottom: 32px;
}
.art-notice-ico {
  flex-shrink: 0; width: 38px; height: 38px;
  background: linear-gradient(135deg,#ef4444,#dc2626);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: .95rem;
}
.art-notice-txt h4 {
  font-size: .95rem; font-weight: 700; margin: 0 0 6px;
  color: rgba(15,23,42,.92);
}
body.dark-mode .art-notice-txt h4 { color: rgba(255,255,255,.9); }
.art-notice-txt p {
  margin: 0; font-size: .875rem; line-height: 1.65;
  color: rgba(15,23,42,.78);
}
body.dark-mode .art-notice-txt p { color: rgba(255,255,255,.75); }

/* ── Article body card ── */
.art-body {
  background: #fff;
  border: 1px solid rgba(6,182,212,.1);
  border-radius: var(--r);
  padding: 52px 56px;
  box-shadow: 0 4px 32px rgba(6,182,212,.06), 0 1px 3px rgba(0,0,0,.04);
  line-height: 1.9; font-size: 1.05rem;
  color: rgba(15,23,42,.88);
  margin-bottom: 40px;
  word-wrap: break-word; overflow-wrap: break-word; overflow-x: hidden;
}
body.dark-mode .art-body {
  background: rgba(15,23,42,.65);
  border-color: rgba(6,182,212,.2);
  color: rgba(255,255,255,.88);
  box-shadow: 0 4px 32px rgba(0,0,0,.25);
}
.art-body * { max-width: 100%; box-sizing: border-box; }

/* Typography inside article */
.art-body h2 {
  font-size: 1.6rem; font-weight: 800;
  color: var(--cd);
  margin: 48px 0 18px;
  padding-bottom: 12px;
  border-bottom: 2px solid rgba(6,182,212,.2);
  line-height: 1.3;
}
body.dark-mode .art-body h2 { color: var(--c); border-bottom-color: rgba(6,182,212,.25); }

.art-body h3 {
  font-size: 1.225rem; font-weight: 700;
  color: #0d9488;
  margin: 36px 0 14px; line-height: 1.4;
}
body.dark-mode .art-body h3 { color: var(--ct); }

.art-body h4 {
  font-size: 1.05rem; font-weight: 700;
  color: rgba(15,23,42,.85);
  margin: 28px 0 10px;
}
body.dark-mode .art-body h4 { color: rgba(255,255,255,.85); }

.art-body p { margin-bottom: 18px; }
.art-body p:last-child { margin-bottom: 0; }

.art-body ul, .art-body ol { margin: 22px 0; padding-left: 28px; }
.art-body li { margin-bottom: 10px; line-height: 1.75; }

.art-body strong { font-weight: 700; color: var(--cd); }
body.dark-mode .art-body strong { color: var(--c); }

.art-body a { color: var(--cd); text-decoration: underline; text-underline-offset: 3px; transition: color .2s; }
.art-body a:hover { color: var(--c); }
body.dark-mode .art-body a { color: var(--c); }

.art-body blockquote {
  margin: 28px 0; padding: 18px 22px;
  border-left: 4px solid var(--c);
  background: rgba(6,182,212,.05);
  border-radius: 0 var(--rs) var(--rs) 0;
  font-style: italic;
  color: rgba(30,41,59,.8);
}
body.dark-mode .art-body blockquote { background: rgba(6,182,212,.08); color: rgba(255,255,255,.8); }

.art-body code {
  background: rgba(6,182,212,.08); color: var(--cd);
  padding: 2px 7px; border-radius: 5px;
  font-size: .87em; font-family: 'Courier New', monospace;
}
body.dark-mode .art-body code { color: var(--c); }

.art-body pre {
  background: rgba(6,182,212,.05);
  border: 1px solid rgba(6,182,212,.15);
  border-radius: var(--rs);
  padding: 24px; overflow-x: auto;
  margin: 28px 0; font-size: .87rem; line-height: 1.65;
}
.art-body pre code { background: none; padding: 0; color: inherit; font-size: inherit; }

.art-body img {
  width: 100%; height: auto;
  border-radius: var(--rs);
  margin: 24px 0; display: block;
  box-shadow: 0 8px 28px rgba(0,0,0,.1);
  /* Les images collées dans le contenu par les auteurs n'ont pas d'attributs
     width/height (contrairement à la cover et aux vignettes) : le navigateur
     ne peut donc pas réserver leur espace avant chargement → CLS. On force un
     ratio 16/9 par défaut (recadré via object-fit) pour réserver la place ;
     ce n'est qu'un compromis — le vrai correctif est d'ajouter width/height
     à la source quand un article est rédigé. */
  aspect-ratio: 16 / 9;
  object-fit: cover;
}
/* Si l'attribut HTML width/height est présent sur l'image, le navigateur
   calcule déjà le bon ratio intrinsèque : ne pas l'écraser avec 16/9. */
.art-body img[width][height] {
  aspect-ratio: auto;
  object-fit: initial;
}

.art-body table {
  width: 100%; border-collapse: collapse;
  margin: 24px 0; font-size: .9rem;
}
.art-body th {
  background: rgba(6,182,212,.1); color: var(--cd);
  font-weight: 700; padding: 12px 15px;
  text-align: left; border: 1px solid rgba(6,182,212,.2);
}
.art-body td {
  padding: 11px 15px; border: 1px solid rgba(6,182,212,.12);
  color: rgba(30,41,59,.85);
}
body.dark-mode .art-body th { color: var(--c); }
body.dark-mode .art-body td { color: rgba(255,255,255,.8); border-color: rgba(6,182,212,.18); }

@media (max-width: 640px) {
  .art-body table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; }
}

.art-body iframe, .art-body video, .art-body embed {
  width: 100%; height: auto;
  border-radius: var(--rs); margin: 24px 0;
}

/* ── Share bar ── */
.art-share {
  padding-top: 28px;
  border-top: 1.5px solid rgba(6,182,212,.14);
}
.art-share-lbl {
  font-size: .72rem; font-weight: 700;
  letter-spacing: .07em; text-transform: uppercase;
  color: rgba(30,41,59,.45); margin-bottom: 14px;
}
body.dark-mode .art-share-lbl { color: rgba(255,255,255,.4); }
.art-share-row { display: flex; gap: 9px; flex-wrap: wrap; }
.art-shr {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 17px; border-radius: 100px; border: none;
  cursor: pointer; font-size: .8rem; font-weight: 600;
  color: #fff; transition: var(--tr);
}
.art-shr:hover { transform: translateY(-2px); filter: brightness(1.1); }
.art-shr[data-share="facebook"]  { background: #1877f2; }
.art-shr[data-share="twitter"]   { background: #1da1f2; }
.art-shr[data-share="linkedin"]  { background: #0077b5; }
.art-shr[data-share="whatsapp"]  { background: #25d366; }
.art-shr[data-share="email"]     { background: var(--cd); }
.art-shr[data-share="copy"]      { background: #6366f1; }

/* ── Sidebar ── */
.art-sidebar {
  position: sticky; top: 84px;
  display: flex; flex-direction: column; gap: 22px;
}

/* Ad card */
.art-sidebar-ad {
  position: relative;
  background: #fff;
  border: 1.5px solid rgba(6,182,212,.18);
  border-radius: var(--r); overflow: hidden;
  transition: var(--tr);
  box-shadow: 0 6px 28px rgba(6,182,212,.07);
}
.art-sidebar-ad a {
  display: block; text-decoration: none; color: inherit;
}
body.dark-mode .art-sidebar-ad {
  background: rgba(15,23,42,.7);
  border-color: rgba(6,182,212,.25);
  box-shadow: 0 6px 28px rgba(0,0,0,.25);
}
.art-sidebar-ad:hover {
  transform: translateY(-4px);
  border-color: var(--c);
  box-shadow: 0 16px 44px rgba(6,182,212,.18);
}
.art-ad-label {
  position: absolute; top: 12px; left: 12px; z-index: 2;
  padding: 4px 11px; border-radius: 999px;
  font-size: .68rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .05em;
  color: #fff; background: rgba(2,6,23,.6);
  backdrop-filter: blur(6px);
}
.art-ad-imgwrap {
  position: relative; width: 100%; overflow: hidden;
  aspect-ratio: 16 / 9; background: #0f172a;
}
.art-ad-imgwrap img {
  width: 100%; height: 100%; display: block;
  object-fit: cover;
  transition: transform .5s ease;
}
.art-sidebar-ad:hover .art-ad-imgwrap img { transform: scale(1.05); }
.art-ad-content { padding: 16px 18px 18px; }
.art-ad-content h4 {
  font-size: .95rem; font-weight: 700;
  color: #0f172a;
  margin: 0 0 5px;
}
body.dark-mode .art-ad-content h4 { color: #f1f5f9; }
.art-ad-content p {
  font-size: .82rem; color: rgba(30,41,59,.65);
  margin: 0 0 12px; line-height: 1.5;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden;
}
body.dark-mode .art-ad-content p { color: rgba(255,255,255,.6); }
.art-ad-cta {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: .78rem; font-weight: 700; color: var(--c);
}
.art-sidebar-ad:hover .art-ad-cta i { transform: translateX(3px); }
.art-ad-cta i { transition: transform .25s ease; }

/* Section label */
.art-sec-lbl {
  font-size: .72rem; font-weight: 700;
  letter-spacing: .07em; text-transform: uppercase;
  color: rgba(30,41,59,.45); margin-bottom: 12px;
  display: flex; align-items: center; gap: 7px;
}
body.dark-mode .art-sec-lbl { color: rgba(255,255,255,.42); }
.art-sec-lbl i { color: var(--c); }

/* Document card */
.art-doc {
  display: flex; gap: 13px; padding: 13px 14px;
  background: rgba(248,250,252,.9);
  border: 1px solid rgba(6,182,212,.12);
  border-radius: var(--rs);
  text-decoration: none; transition: var(--tr);
  margin-bottom: 9px;
}
body.dark-mode .art-doc {
  background: rgba(15,23,42,.65);
  border-color: rgba(6,182,212,.2);
}
.art-doc:hover {
  border-color: var(--c);
  transform: translateX(4px);
  box-shadow: 0 4px 18px rgba(6,182,212,.12);
}
.art-doc-thumb {
  width: 68px; height: 68px;
  border-radius: 8px; object-fit: cover; flex-shrink: 0;
}
.art-doc-info { flex: 1; min-width: 0; }
.art-doc-name {
  font-size: .855rem; font-weight: 600;
  color: rgba(15,23,42,.9); line-height: 1.4;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; margin-bottom: 7px;
}
body.dark-mode .art-doc-name { color: rgba(255,255,255,.9); }
.art-doc-meta {
  display: flex; align-items: center; gap: 7px;
  font-size: .72rem; color: rgba(30,41,59,.5); flex-wrap: wrap;
}
body.dark-mode .art-doc-meta { color: rgba(255,255,255,.45); }
.badge-free {
  padding: 2px 7px;
  background: linear-gradient(135deg,#10b981,#059669);
  color: #fff; font-size: .65rem; font-weight: 700; border-radius: 4px;
}
.badge-price {
  padding: 2px 8px;
  background: rgba(6,182,212,.08);
  border: 1px solid rgba(6,182,212,.22);
  color: var(--cd); font-size: .7rem; font-weight: 600; border-radius: 4px;
}
body.dark-mode .badge-price { color: var(--c); }

/* ── Related articles ── */
.art-related {
  margin-top: 68px; padding-top: 64px;
  border-top: 1.5px solid rgba(6,182,212,.14);
}
.art-related-hd { text-align: center; margin-bottom: 44px; }
.art-related-eyebrow {
  display: inline-block;
  font-size: .72rem; font-weight: 700;
  letter-spacing: .1em; text-transform: uppercase;
  color: var(--c); margin-bottom: 10px;
}
.art-related-title {
  font-size: 2rem; font-weight: 900; margin: 0;
  background: linear-gradient(135deg, #06b6d4, #14b8a6);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
}
.art-related-grid {
  display: grid; grid-template-columns: repeat(3,1fr); gap: 24px;
}
.art-rel-card {
  position: relative;
  background: #fff;
  border: 1.5px solid rgba(6,182,212,.12);
  border-radius: var(--r); overflow: visible;
  text-decoration: none; transition: var(--tr);
  box-shadow: 0 4px 18px rgba(6,182,212,.05);
}
body.dark-mode .art-rel-card {
  background: rgba(15,23,42,.6);
  border-color: rgba(6,182,212,.2);
  box-shadow: 0 4px 18px rgba(0,0,0,.2);
}
.art-rel-card:hover {
  transform: translateY(-8px);
  border-color: var(--c);
  box-shadow: 0 22px 52px rgba(6,182,212,.18);
}
.art-rel-card-link {
  position: absolute; inset: 0; z-index: 1; border-radius: inherit;
}
.art-rel-img-wrap {
  position: relative; height: 198px; overflow: hidden;
  border-radius: var(--r) var(--r) 0 0;
  background: linear-gradient(135deg, rgba(6,182,212,.18), rgba(20,184,166,.18));
}
.art-rel-img-wrap img { pointer-events: none; }
.art-rel-img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .4s ease;
}
.art-rel-card:hover .art-rel-img { transform: scale(1.06); }
.art-rel-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, transparent 50%, rgba(2,6,23,.55) 100%);
  display: flex; align-items: flex-end; justify-content: flex-end;
  padding: 12px; opacity: 0; transition: opacity .3s;
}
.art-rel-card:hover .art-rel-overlay { opacity: 1; }
.art-rel-overlay span {
  font-size: .75rem; font-weight: 700; color: #fff;
  display: flex; align-items: center; gap: 5px;
}
.art-rel-body { padding: 18px 20px; }
.art-rel-cat {
  font-size: .7rem; font-weight: 700;
  letter-spacing: .06em; text-transform: uppercase;
  color: var(--c); margin-bottom: 8px; display: block;
}
.art-rel-ttl {
  font-size: .975rem; font-weight: 700; line-height: 1.45;
  color: rgba(15,23,42,.9);
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; margin-bottom: 12px;
}
body.dark-mode .art-rel-ttl { color: rgba(255,255,255,.9); }
.art-rel-meta {
  display: flex; align-items: center; gap: 13px;
  font-size: .76rem; color: rgba(30,41,59,.48);
}
body.dark-mode .art-rel-meta { color: rgba(255,255,255,.43); }

/* ── Related article — share button ── */
.art-rel-share { position: absolute; top: 12px; right: 12px; z-index: 3; }
.art-rel-share-toggle {
  width: 36px; height: 36px; border: none; outline: none; box-shadow: none;
  -webkit-appearance: none; appearance: none; padding: 0;
  background: transparent;
  color: #fff; text-shadow: 0 2px 6px rgba(0,0,0,.5);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: var(--tr); font-size: 1.05rem;
}
.art-rel-share-toggle:focus,
.art-rel-share-toggle:focus-visible { outline: none; box-shadow: none; }
.art-rel-share-toggle:hover,
.art-rel-share.is-open .art-rel-share-toggle {
  color: var(--c); transform: scale(1.15);
}
.art-rel-share-menu {
  position: absolute; top: 44px; right: 0;
  display: flex; flex-direction: column; gap: 6px; padding: 10px;
  border-radius: var(--rs); background: #fff;
  border: 1px solid rgba(6,182,212,.2);
  box-shadow: 0 18px 45px rgba(15,23,42,.18);
  opacity: 0; visibility: hidden; transform: translateY(-8px) scale(.95);
  transition: all .22s ease; min-width: 168px;
}
body.dark-mode .art-rel-share-menu {
  background: rgba(15,23,42,.97); border-color: rgba(6,182,212,.3);
  box-shadow: 0 18px 45px rgba(0,0,0,.45);
}
.art-rel-share.is-open .art-rel-share-menu {
  opacity: 1; visibility: visible; transform: translateY(0) scale(1);
}
.art-rel-share-menu a, .art-rel-share-menu button {
  display: flex; align-items: center; gap: 10px; padding: 8px 10px;
  border-radius: 10px; border: none; background: transparent;
  color: #334155; font-size: .83rem; font-weight: 600;
  text-decoration: none; cursor: pointer; text-align: left; width: 100%;
}
body.dark-mode .art-rel-share-menu a,
body.dark-mode .art-rel-share-menu button { color: #cbd5e1; }
.art-rel-share-menu a:hover, .art-rel-share-menu button:hover {
  background: rgba(6,182,212,.15); color: var(--c);
}
.art-rel-share-menu i { width: 16px; text-align: center; }
.art-rel-share-menu i.fa-facebook-f { color: #1877f2; }
.art-rel-share-menu i.fa-whatsapp { color: #25d366; }
.art-rel-share-menu i.fa-linkedin-in { color: #0a66c2; }
.art-rel-share-menu i.fa-link { color: var(--c); }

/* ── Responsive ── */
@media (max-width: 1100px) {
  .art-layout { grid-template-columns: 1fr 320px; gap: 38px; }
}
@media (max-width: 880px) {
  .art-layout { grid-template-columns: 1fr; }
  .art-sidebar { position: static; }
  .art-related-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 640px) {
  .art-page { padding: 28px 16px 60px; }
  .art-body { padding: 26px 18px; font-size: .975rem; }
  .art-hero-inner, .art-hero-none-inner { padding: 0 16px 44px; }
  .art-hero { height: 54vh; min-height: 400px; }
  .art-hero-title { font-size: clamp(1.65rem, 6vw, 2.4rem); }
  .art-related-grid { grid-template-columns: 1fr; }
  .art-shr span { display: none; }
  .art-shr { padding: 9px 13px; }
  .art-hero-none-inner { padding: 56px 16px; }
}
@media (max-width: 400px) {
  .art-hero { min-height: 360px; }
  .art-hero-title { font-size: 1.6rem; }
}

/* ── Comments & misc overrides ── */
.comments-section {
  background: rgba(15,23,42,.55) !important;
  backdrop-filter: blur(20px) !important;
  border-color: rgba(6,182,212,.2) !important;
}
body:not(.dark-mode) .comments-section {
  background: rgba(255,255,255,.95) !important;
  border-color: rgba(6,182,212,.22) !important;
  box-shadow: 0 4px 20px rgba(6,182,212,.06) !important;
}
.comments-title { color: var(--c) !important; }
body:not(.dark-mode) .comments-title { color: rgba(15,23,42,.9) !important; }
.comment-form-wrapper {
  background: rgba(30,41,59,.5) !important;
  border-color: rgba(6,182,212,.28) !important;
}
body:not(.dark-mode) .comment-form-wrapper {
  background: rgba(248,250,252,.95) !important;
  border-color: rgba(6,182,212,.22) !important;
}
.comment-form-wrapper label { color: rgba(255,255,255,.9) !important; }
body:not(.dark-mode) .comment-form-wrapper label { color: rgba(15,23,42,.85) !important; }
.comment-form-wrapper input,
.comment-form-wrapper textarea {
  background: rgba(30,41,59,.65) !important;
  border-color: rgba(6,182,212,.35) !important;
  color: #fff !important;
}
body:not(.dark-mode) .comment-form-wrapper input,
body:not(.dark-mode) .comment-form-wrapper textarea {
  background: rgba(255,255,255,.95) !important;
  border-color: rgba(6,182,212,.25) !important;
  color: rgba(15,23,42,.9) !important;
}
</style>
@endsection


@section('content')
<div id="art-progress"></div>

@php
    $wordCount   = str_word_count(strip_tags($article->content));
    $readingTime = max(1, ceil($wordCount / 200));

    // ── TÂCHE 5 : Découpe du contenu pour injection de l'e-book ──────────────
    // On convertit le markdown d'abord, puis on découpe au 3e </p>
    $renderedContent = markdown_to_html($article->content ?? '');
    // Le H1 de la page est dans le hero — tout H1 dans le corps devient H2
    $renderedContent = preg_replace('/<h1(\b[^>]*)>/i', '<h2$1>', $renderedContent);
    $renderedContent = preg_replace('/<\/h1>/i', '</h2>', $renderedContent);
    // Corriger l'ancienne URL /ebooks/decodeur-carriere (route supprimée) → document réel
    $decodeurPath    = '/documents/le-decodeur-de-carriere-guide-ultime-pour-decrocher-un-emploi';
    $renderedContent = str_replace(
        ['/ebooks/decodeur-carriere', 'niangprogrammeur.com/ebooks/decodeur-carriere'],
        [$decodeurPath,               'niangprogrammeur.com' . $decodeurPath],
        $renderedContent
    );
    $cParts          = explode('</p>', $renderedContent);
    $cSplitAt        = min(3, max(0, count($cParts) - 1));
    $cBefore         = implode('</p>', array_slice($cParts, 0, $cSplitAt))
                       . ($cSplitAt > 0 ? '</p>' : '');
    $cAfter          = implode('</p>', array_slice($cParts, $cSplitAt));
@endphp

{{-- ── HERO ── --}}
@if($article->cover_image)
<div class="art-hero">
  <img class="art-hero-bg"
       src="{{ $article->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($article->cover_image) : $article->cover_image }}"
       alt="{{ $article->title }}"
       width="1200" height="630"
       loading="eager"
       fetchpriority="high"
       decoding="async">
  <div class="art-hero-veil"></div>

  <div class="art-fav-wrap">
    <button class="art-fav-btn"
            data-favorite
            data-favorite-type="article"
            data-favorite-slug="{{ $article->slug }}"
            data-favorite-name="{{ $article->title }}">
      <i class="far fa-heart"></i>
      <span>Favoris</span>
    </button>
  </div>

  <div class="art-hero-inner">
    @if($article->category)
    <span class="art-badge"><i class="{{ $article->category->icon ?? 'fas fa-folder' }}"></i> {{ $article->category->name }}</span>
    @endif
    <h1 class="art-hero-title">{{ $article->title }}</h1>
    <div class="art-meta-row">
      @if($article->published_at)
      <time class="art-chip" datetime="{{ $article->published_at->toIso8601String() }}"><i class="fas fa-calendar"></i> {{ $article->published_at->translatedFormat('d F Y') }}</time>
      @endif
      <span class="art-chip"><i class="fas fa-eye"></i> {{ $article->featured_display_views }}</span>
      <span class="art-chip"><i class="fas fa-clock"></i> {{ $readingTime }} min de lecture</span>
      <span class="art-chip"><i class="fas fa-user"></i> NiangProgrammeur</span>
    </div>
  </div>
</div>
@else
<div class="art-hero-none">
  <div class="art-fav-wrap">
    <button class="art-fav-btn"
            data-favorite
            data-favorite-type="article"
            data-favorite-slug="{{ $article->slug }}"
            data-favorite-name="{{ $article->title }}">
      <i class="far fa-heart"></i>
      <span>Favoris</span>
    </button>
  </div>
  <div class="art-hero-none-inner">
    @if($article->category)
    <span class="art-badge"><i class="{{ $article->category->icon ?? 'fas fa-folder' }}"></i> {{ $article->category->name }}</span>
    @endif
    <h1 class="art-hero-title">{{ $article->title }}</h1>
    <div class="art-meta-row">
      @if($article->published_at)
      <time class="art-chip" datetime="{{ $article->published_at->toIso8601String() }}"><i class="fas fa-calendar"></i> {{ $article->published_at->translatedFormat('d F Y') }}</time>
      @endif
      <span class="art-chip"><i class="fas fa-eye"></i> {{ $article->featured_display_views }}</span>
      <span class="art-chip"><i class="fas fa-clock"></i> {{ $readingTime }} min de lecture</span>
      <span class="art-chip"><i class="fas fa-user"></i> NiangProgrammeur</span>
    </div>
  </div>
</div>
@endif

{{-- ── MAIN ── --}}
<div class="art-page">
  <div class="art-layout">

    {{-- Left: article content --}}
    <div>
      <a href="{{ route('emplois.offres') }}" class="art-back">
        <i class="fas fa-arrow-left"></i> Retour aux offres
      </a>

      <div class="art-notice">
        <div class="art-notice-ico"><i class="fas fa-info-circle"></i></div>
        <div class="art-notice-txt">
          <h4>{{ app()->getLocale() === 'fr' ? 'Note importante' : 'Important Note' }}</h4>
          <p>{{ app()->getLocale() === 'fr'
            ? 'NiangProgrammeur ne recrute pas directement. Nous partageons uniquement les offres d\'emploi, bourses d\'études et opportunités disponibles au Sénégal. Pour postuler, veuillez contacter directement l\'organisme ou l\'entreprise concernée via les coordonnées fournies dans l\'article.'
            : 'NiangProgrammeur does not recruit directly. We only share job offers, scholarships and opportunities available in Senegal. To apply, please contact the organization or company directly using the contact information provided in the article.' }}</p>
        </div>
      </div>

      {{-- ── TÂCHE 1+5 : Contenu + injection E-book au 3e paragraphe ── --}}
      <div class="art-body">
        {!! $cBefore !!}
        @if($cSplitAt > 0)
          {{-- TÂCHE 5 : Encadré e-book natif (seul lien inter-silos autorisé) --}}
          <x-ebook-promo :type="$contentType" />
        @endif
        {!! $cAfter !!}

        {{-- TÂCHE 1 : Bloc Conseil Expert NiangProgrammeur (anti-thin-content) --}}
        @if(!empty($expertAdvice))
        <div class="np-expert-box" role="complementary" aria-label="Conseil Expert NiangProgrammeur">
        <style>
        .np-expert-box{
            margin:2rem 0 0;
            padding:1.4rem 1.6rem;
            background:linear-gradient(135deg,rgba(5,150,105,.07),rgba(8,145,178,.07));
            border-left:5px solid #059669;
            border-radius:0 12px 12px 0;
        }
        .np-expert-box-hd{
            display:flex;align-items:center;gap:.55rem;
            font-size:.78rem;font-weight:800;
            color:#059669;text-transform:uppercase;letter-spacing:.07em;
            margin-bottom:.6rem;
        }
        body.dark-mode .np-expert-box-hd{color:#34d399;}
        .np-expert-box p{
            margin:0;font-size:.9rem;line-height:1.7;
            color:rgba(30,41,59,.8);
        }
        body.dark-mode .np-expert-box p{color:rgba(255,255,255,.75);}
        body.dark-mode .np-expert-box{background:rgba(5,150,105,.09);}
        </style>
            <div class="np-expert-box-hd">
                <i class="fas fa-lightbulb"></i> Conseil de l'Expert NiangProgrammeur
            </div>
            <p>{{ $expertAdvice }}</p>
        </div>
        @endif

        {{-- TÂCHE 3 : FAQ visible dans l'article + balisage microdata --}}
        @if(!empty($articleFaqs))
          <x-faq-schema :faqs="$articleFaqs" />
        @endif
      </div>

      <div class="art-share">
        <div class="art-share-lbl">Partager cet article</div>
        <div class="art-share-row">
          <button class="art-shr" data-share="facebook"
                  data-share-url="{{ $articleUrl }}"
                  data-share-title="{{ $article->title }}"
                  data-share-text="{{ $article->excerpt ?? '' }}">
            <i class="fab fa-facebook"></i><span>Facebook</span>
          </button>
          <button class="art-shr" data-share="twitter"
                  data-share-url="{{ $articleUrl }}"
                  data-share-title="{{ $article->title }}"
                  data-share-text="{{ $article->excerpt ?? '' }}">
            <i class="fab fa-twitter"></i><span>Twitter</span>
          </button>
          <button class="art-shr" data-share="linkedin"
                  data-share-url="{{ $articleUrl }}"
                  data-share-title="{{ $article->title }}"
                  data-share-text="{{ $article->excerpt ?? '' }}">
            <i class="fab fa-linkedin"></i><span>LinkedIn</span>
          </button>
          <button class="art-shr" data-share="whatsapp"
                  data-share-url="{{ $articleUrl }}"
                  data-share-title="{{ $article->title }}"
                  data-share-text="{{ $article->excerpt ?? '' }}">
            <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
          </button>
          <button class="art-shr" data-share="email"
                  data-share-url="{{ $articleUrl }}"
                  data-share-title="{{ $article->title }}"
                  data-share-text="{{ $article->excerpt ?? '' }}">
            <i class="fas fa-envelope"></i><span>Email</span>
          </button>
          <button class="art-shr" data-share="copy"
                  data-share-url="{{ $articleUrl }}">
            <i class="fas fa-link"></i><span>Copier</span>
          </button>
        </div>
      </div>
    </div>

    {{-- Right: sidebar --}}
    <aside class="art-sidebar">

      @if(isset($sidebarAds) && $sidebarAds->count() > 0)
        @foreach($sidebarAds as $ad)
        @if($ad->isVideoAd())
          @include('partials.youtube-ad-card', ['ad' => $ad, 'label' => 'Sponsorisé'])
        @else
        <div class="art-sidebar-ad">
          <a href="{{ $ad->link_url ?? '#' }}" target="_blank" rel="noopener" onclick="trackAdClick({{ $ad->id }})">
            <span class="art-ad-label">Sponsorisé</span>
            @if($ad->image)
            <div class="art-ad-imgwrap">
              <img src="{{ $ad->image_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($ad->image) : $ad->image }}"
                   alt="{{ $ad->name }}" width="400" height="225" loading="lazy" decoding="async"
                   onerror="this.style.display='none'">
            </div>
            @endif
            <div class="art-ad-content">
              <h4>{{ $ad->name }}</h4>
              @if($ad->description)<p>{{ $ad->description }}</p>@endif
              <span class="art-ad-cta">Découvrir <i class="fas fa-arrow-right"></i></span>
            </div>
          </a>
        </div>
        @endif
        @php $ad->incrementImpressions(); @endphp
        @endforeach
      @endif

      @if(isset($relatedDocuments) && $relatedDocuments->count() > 0)
      <div>
        <div class="art-sec-lbl"><i class="fas fa-file-alt"></i> Documents pertinents</div>
        @foreach($relatedDocuments as $document)
        <a href="{{ route('documents.show', $document->slug) }}" class="art-doc">
          @if($document->cover_image)
          <img class="art-doc-thumb"
               src="{{ $document->cover_type === 'internal' ? \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id]) : $document->cover_image }}"
               alt="{{ $document->title }}" width="68" height="68"
               loading="lazy" decoding="async"
               onerror="this.style.display='none'">
          @endif
          <div class="art-doc-info">
            <div class="art-doc-name">{{ $document->title }}</div>
            <div class="art-doc-meta">
              @if($document->is_free)
                <span class="badge-free">GRATUIT</span>
              @else
                <span class="badge-price">{{ number_format($document->discount_price ?? $document->price, 0, ',', ' ') }} FCFA</span>
              @endif
              @if($document->category)
              <span><i class="fas fa-folder" style="color:#06b6d4"></i> {{ $document->category->name }}</span>
              @endif
              @if($document->sales_count > 0)
              <span><i class="fas fa-shopping-cart"></i> {{ $document->sales_count }} {{ $document->sales_count > 1 ? 'ventes' : 'vente' }}</span>
              @endif
            </div>
          </div>
        </a>
        @endforeach
      </div>
      @endif

      @include('partials.comments', ['commentable' => $article, 'comments' => $comments ?? []])

    </aside>
  </div>

  {{-- Related articles --}}
  @if($relatedArticles && $relatedArticles->count() > 0)
  <div class="art-related">
    <div class="art-related-hd">
      <div class="art-related-eyebrow"><i class="fas fa-newspaper"></i> À lire aussi</div>
      <h2 class="art-related-title">Articles Similaires</h2>
    </div>
    <div class="art-related-grid">
      @foreach($relatedArticles as $related)
      @php $relatedUrl = route('emplois.article', $related->slug); @endphp
      <div class="art-rel-card">
        <a href="{{ $relatedUrl }}" class="art-rel-card-link" aria-label="{{ $related->title }}"></a>
        <div class="art-rel-share" data-share-card>
          <button type="button" class="art-rel-share-toggle" data-share-toggle aria-haspopup="true" aria-expanded="false" aria-label="Partager cet article">
            <i class="fas fa-share-alt"></i>
          </button>
          <div class="art-rel-share-menu" data-share-menu>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($relatedUrl) }}" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($relatedUrl) }}&text={{ urlencode($related->title) }}" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-twitter"></i> X (Twitter)
            </a>
            <a href="https://wa.me/?text={{ urlencode($related->title . ' ' . $relatedUrl) }}" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($relatedUrl) }}&title={{ urlencode($related->title) }}" target="_blank" rel="noopener noreferrer">
              <i class="fab fa-linkedin-in"></i> LinkedIn
            </a>
            <button type="button" data-copy-link="{{ $relatedUrl }}">
              <i class="fas fa-link"></i> Copier le lien
            </button>
          </div>
        </div>
        <div class="art-rel-img-wrap">
          @if($related->cover_image)
          <img class="art-rel-img"
               src="{{ $related->cover_type === 'internal' ? \Illuminate\Support\Facades\Storage::url($related->cover_image) : $related->cover_image }}"
               alt="{{ $related->title }}" width="352" height="198"
               loading="lazy" decoding="async"
               onerror="this.parentElement.style.background='linear-gradient(135deg,rgba(6,182,212,.18),rgba(20,184,166,.18))'">
          @endif
          <div class="art-rel-overlay"><span>Lire l'article <i class="fas fa-arrow-right"></i></span></div>
        </div>
        <div class="art-rel-body">
          @if($related->category)
          <span class="art-rel-cat">{{ $related->category->name }}</span>
          @endif
          <div class="art-rel-ttl">{{ $related->title }}</div>
          <div class="art-rel-meta">
            @if($related->published_at)
            <time datetime="{{ $related->published_at->toIso8601String() }}"><i class="fas fa-calendar"></i> {{ $related->published_at->format('d/m/Y') }}</time>
            @endif
            <span><i class="fas fa-eye"></i> {{ $related->featured_display_views }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>

<script>
  (function() {
    var bar = document.getElementById('art-progress');
    if (!bar) return;
    window.addEventListener('scroll', function() {
      var doc = document.documentElement;
      var scrolled = doc.scrollTop || document.body.scrollTop;
      var total = (doc.scrollHeight || document.body.scrollHeight) - doc.clientHeight;
      bar.style.width = total > 0 ? Math.min(100, (scrolled / total) * 100) + '%' : '0%';
    }, { passive: true });
  })();
</script>

<script>
(function () {
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('[data-share-toggle]');
        const copyBtn = e.target.closest('[data-copy-link]');

        if (copyBtn) {
            e.preventDefault();
            e.stopPropagation();
            const url = copyBtn.getAttribute('data-copy-link');
            const finish = () => {
                const original = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Lien copié !';
                setTimeout(() => { copyBtn.innerHTML = original; }, 1800);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(finish).catch(finish);
            } else {
                const ta = document.createElement('textarea');
                ta.value = url;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                finish();
            }
            return;
        }

        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            const wrapper = toggle.closest('[data-share-card]');
            const isOpen = wrapper.classList.contains('is-open');
            document.querySelectorAll('[data-share-card].is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('[data-share-toggle]').setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                wrapper.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
            }
            return;
        }

        if (!e.target.closest('[data-share-menu]')) {
            document.querySelectorAll('[data-share-card].is-open').forEach(function (el) {
                el.classList.remove('is-open');
                el.querySelector('[data-share-toggle]').setAttribute('aria-expanded', 'false');
            });
        }
    });

    document.querySelectorAll('[data-share-menu]').forEach(function (menu) {
        menu.addEventListener('click', function (e) {
            if (e.target.closest('a')) {
                e.stopPropagation();
            }
        });
    });
})();
</script>

<script>
  function trackAdClick(adId) {
    fetch('/api/ads/' + adId + '/click', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Content-Type': 'application/json'
      }
    }).catch(function(e) { console.log('Ad click tracking error:', e); });
  }
</script>

<script>
  (function() {
    function initSocialShare() {
      if (typeof SocialShareManager !== 'undefined') {
        if (!window.socialShareManager) {
          window.socialShareManager = new SocialShareManager();
        }
      } else {
        setTimeout(initSocialShare, 100);
      }
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initSocialShare);
    } else {
      initSocialShare();
    }
    window.addEventListener('load', function() {
      if (typeof SocialShareManager !== 'undefined' && !window.socialShareManager) {
        window.socialShareManager = new SocialShareManager();
      }
    });
  })();
</script>
@endsection
