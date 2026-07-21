@extends('layouts.app')

@section('title', ($document->meta_title ?? $document->title) . ' - Documents - NiangProgrammeur')
@section('meta_description', $document->meta_description ?? $document->excerpt ?? $document->description ?? 'Découvrez ce document sur NiangProgrammeur')

@push('head')
@php
    $documentUrl = url()->current();
    $documentImage = $document->cover_image
        ? ($document->cover_type === 'internal'
            ? \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $document->id])
            : $document->cover_image)
        : asset('images/logo.png');
    if (!preg_match('/^https?:\/\//', $documentImage)) {
        $documentImage = url($documentImage);
    }
    $documentDescription = htmlspecialchars($document->meta_description ?? $document->excerpt ?? $document->description ?? 'Découvrez ce document sur NiangProgrammeur', ENT_QUOTES, 'UTF-8');
    $documentTitle = htmlspecialchars($document->meta_title ?? $document->title, ENT_QUOTES, 'UTF-8');
    $documentPrice = $document->discount_price ?? $document->price;
    $documentCurrency = 'XOF';
@endphp

@section('canonical', $documentUrl)

@if($document->meta_keywords)
<meta name="keywords" content="{{ is_array($document->meta_keywords) ? implode(', ', $document->meta_keywords) : $document->meta_keywords }}">
@endif
<meta property="og:type" content="product">
<meta property="og:url" content="{{ $documentUrl }}">
<meta property="og:title" content="{{ $documentTitle }}">
<meta property="og:description" content="{{ $documentDescription }}">
<meta property="og:image" content="{{ $documentImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $documentTitle }}">
<meta property="og:site_name" content="NiangProgrammeur">
<meta property="og:locale" content="fr_FR">
@if($document->price)
<meta property="product:price:amount" content="{{ $documentPrice }}">
<meta property="product:price:currency" content="{{ $documentCurrency }}">
@endif
@if($document->category)
<meta property="product:category" content="{{ $document->category->name }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $documentUrl }}">
<meta name="twitter:title" content="{{ $documentTitle }}">
<meta name="twitter:description" content="{{ $documentDescription }}">
<meta name="twitter:image" content="{{ $documentImage }}">
<meta name="twitter:image:alt" content="{{ $documentTitle }}">
<meta name="twitter:site" content="@niangprogrammeur">
<meta name="twitter:creator" content="@niangprogrammeur">

@php
    $productSchema = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $document->title,
        "description" => $documentDescription,
        "image" => $documentImage,
        "url" => $documentUrl,
        "brand" => ["@type" => "Brand", "name" => "NiangProgrammeur"]
    ];
    $reviewsCount = (int) ($document->reviews_count ?? 0);
    if ($reviewsCount > 0) {
        $productSchema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => (string) number_format($document->average_rating ?? 0, 1),
            "reviewCount" => $reviewsCount,
            "bestRating" => "5"
        ];
    }
    if ($document->category) $productSchema["category"] = $document->category->name;
    if ($document->price || $document->isFree()) {
        $productSchema["offers"] = [
            "@type" => "Offer",
            "url" => $documentUrl,
            "priceCurrency" => $documentCurrency,
            "price" => (string) ($documentPrice ?? 0),
            "validFrom" => ($document->published_at ?? $document->created_at)->format('Y-m-d'),
            "priceValidUntil" => now()->addYear()->format('Y-m-d'),
            "availability" => "https://schema.org/InStock",
            "seller" => ["@type" => "Organization", "name" => "NiangProgrammeur"],
            "shippingDetails" => [
                "@type" => "OfferShippingDetails",
                "shippingRate" => ["@type" => "MonetaryAmount", "value" => "0", "currency" => $documentCurrency],
                "shippingDestination" => ["@type" => "DefinedRegion", "addressCountry" => "SN"],
                "deliveryTime" => [
                    "@type" => "ShippingDeliveryTime",
                    "handlingTime" => ["@type" => "QuantitativeValue", "minValue" => 0, "maxValue" => 0, "unitCode" => "DAY"],
                    "transitTime" => ["@type" => "QuantitativeValue", "minValue" => 0, "maxValue" => 0, "unitCode" => "DAY"]
                ]
            ],
            "hasMerchantReturnPolicy" => ["@type" => "MerchantReturnPolicy", "applicableCountry" => "SN", "returnPolicyCategory" => "https://schema.org/MerchantReturnNotPermitted"]
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}</script>

@if(in_array(strtolower($document->file_extension ?? ''), ['pdf', 'epub', 'mobi']))
@php
    $bookSchema = [
        "@context" => "https://schema.org",
        "@type" => "Book",
        "name" => $document->title,
        "description" => $documentDescription,
        "image" => $documentImage,
        "url" => $documentUrl,
        "publisher" => ["@type" => "Organization", "name" => "NiangProgrammeur", "logo" => ["@type" => "ImageObject", "url" => asset('images/logo.png')]],
        "datePublished" => ($document->published_at ? $document->published_at->format('Y-m-d') : $document->created_at->format('Y-m-d')),
        "dateModified" => $document->updated_at->format('Y-m-d'),
        "inLanguage" => "fr-FR",
        "bookFormat" => "https://schema.org/EBook"
    ];
    if ($document->author) $bookSchema["author"] = ["@type" => "Person", "name" => $document->author->name];
@endphp
<script type="application/ld+json">{!! json_encode($bookSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_PRETTY_PRINT) !!}</script>
@endif
@endpush

@if($document->cover_image)
@push('preload_images')
<link rel="preload" as="image" href="{{ $documentImage }}" fetchpriority="high">
@endpush
@endif

@push('styles')
<style>
/* ═══════════════════════════════════════════
   VARIABLES
   ═══════════════════════════════════════════ */
:root {
  --doc-cyan:    #06b6d4;
  --doc-teal:    #14b8a6;
  --doc-cyan-10: rgba(6,182,212,.10);
  --doc-cyan-20: rgba(6,182,212,.20);
  --doc-cyan-30: rgba(6,182,212,.30);
  --doc-green:   #10b981;
  --doc-amber:   #f59e0b;
  --doc-red:     #ef4444;
  --doc-radius:  20px;
  --doc-glass-light: rgba(255,255,255,.80);
  --doc-glass-border: rgba(6,182,212,.18);
  --doc-text:    #1e293b;
  --doc-muted:   #64748b;
  --doc-bg:      #f0f7ff;
}

/* ═══════════════════════════════════════════
   PAGE WRAPPER
   ═══════════════════════════════════════════ */
.document-detail-page {
  min-height: 100vh;
  padding: calc(var(--spacing-navbar, 76px) + 2.5rem) 1.25rem 4rem;
  position: relative;
  background:
    radial-gradient(ellipse 80% 50% at 20% 0%, rgba(6,182,212,.08) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 100%, rgba(20,184,166,.07) 0%, transparent 55%),
    linear-gradient(160deg, #f0f9ff 0%, #e0f2fe 40%, #f0fdf4 100%);
  overflow: hidden;
}

/* Orbes d'arrière-plan */
.document-detail-page::before,
.document-detail-page::after {
  content: '';
  position: fixed;
  border-radius: 50%;
  pointer-events: none;
  z-index: 0;
  filter: blur(80px);
  opacity: .25;
}
.document-detail-page::before {
  width: 500px; height: 500px;
  top: -150px; left: -100px;
  background: radial-gradient(circle, #06b6d4, transparent 70%);
  animation: orbFloat 12s ease-in-out infinite;
}
.document-detail-page::after {
  width: 400px; height: 400px;
  bottom: -100px; right: -100px;
  background: radial-gradient(circle, #14b8a6, transparent 70%);
  animation: orbFloat 16s ease-in-out infinite reverse;
}

@keyframes orbFloat {
  0%,100% { transform: translate(0,0) scale(1); }
  33%      { transform: translate(30px,-40px) scale(1.05); }
  66%      { transform: translate(-20px,25px) scale(.97); }
}

/* ═══════════════════════════════════════════
   GRID PRINCIPAL
   ═══════════════════════════════════════════ */
.document-detail-container {
  max-width: 1240px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 2rem;
  position: relative;
  z-index: 1;
}

/* ═══════════════════════════════════════════
   COLONNE PRINCIPALE
   ═══════════════════════════════════════════ */
.document-main {
  background: var(--doc-glass-light);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-radius: var(--doc-radius);
  border: 1px solid var(--doc-glass-border);
  padding: 0;
  overflow: hidden;
  box-shadow:
    0 4px 6px rgba(0,0,0,.04),
    0 20px 48px rgba(6,182,212,.07),
    inset 0 1px 0 rgba(255,255,255,.9);
  transition: box-shadow .3s ease;
}
.document-main:hover {
  box-shadow:
    0 8px 16px rgba(0,0,0,.06),
    0 32px 64px rgba(6,182,212,.12),
    inset 0 1px 0 rgba(255,255,255,.9);
}

/* ═══════════════════════════════════════════
   COVER IMAGE
   ═══════════════════════════════════════════ */
.document-cover-large {
  width: 100%;
  height: 360px;
  object-fit: cover;
  display: block;
  border-radius: 0;
  margin-bottom: 0;
  border-bottom: 3px solid transparent;
  background-image: linear-gradient(135deg, var(--doc-cyan), var(--doc-teal));
  transition: transform .4s ease;
}
.document-cover-large.doc-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 5rem;
  color: rgba(255,255,255,.9);
  background: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #14b8a6 100%);
  text-shadow: 0 4px 20px rgba(0,0,0,.2);
}

/* Bande de couleur sous l'image */
.doc-cover-accent {
  height: 4px;
  background: linear-gradient(90deg, var(--doc-cyan), var(--doc-teal), var(--doc-cyan));
  background-size: 200% 100%;
  animation: shimmerLine 3s linear infinite;
}
@keyframes shimmerLine {
  0%   { background-position: 0% 0%; }
  100% { background-position: 200% 0%; }
}

/* Corps du document */
.doc-body {
  padding: 2rem 2.25rem 2.5rem;
}

/* ═══════════════════════════════════════════
   BADGE CATÉGORIE
   ═══════════════════════════════════════════ */
.document-category-badge {
  display: inline-flex;
  align-items: center;
  gap: .45rem;
  padding: .4rem 1rem;
  background: linear-gradient(135deg, var(--doc-cyan-10), rgba(20,184,166,.10));
  color: var(--doc-cyan);
  border: 1px solid var(--doc-cyan-20);
  border-radius: 50px;
  font-size: .8rem;
  font-weight: 700;
  letter-spacing: .04em;
  text-transform: uppercase;
  margin-bottom: 1.1rem;
  transition: all .3s ease;
}
.document-category-badge:hover {
  background: linear-gradient(135deg, var(--doc-cyan-20), rgba(20,184,166,.20));
  transform: translateY(-1px);
}

/* ═══════════════════════════════════════════
   TITRE
   ═══════════════════════════════════════════ */
.document-title-large {
  font-size: clamp(1.6rem, 3vw, 2.2rem);
  font-weight: 900;
  line-height: 1.25;
  margin-bottom: 1.25rem;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #0891b2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: -.01em;
}

/* ═══════════════════════════════════════════
   META
   ═══════════════════════════════════════════ */
.document-meta {
  display: flex;
  flex-wrap: wrap;
  gap: .6rem 1.5rem;
  margin-bottom: 1.75rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(6,182,212,.12);
}
.meta-item {
  display: flex;
  align-items: center;
  gap: .45rem;
  color: var(--doc-muted);
  font-size: .85rem;
  font-weight: 500;
}
.meta-item i {
  color: var(--doc-cyan);
  font-size: .9rem;
  width: 16px;
  text-align: center;
}

/* ═══════════════════════════════════════════
   DESCRIPTION
   ═══════════════════════════════════════════ */
.document-description {
  color: #334155;
  line-height: 1.8;
  font-size: 1rem;
  margin-bottom: 2rem;
}

/* ═══════════════════════════════════════════
   INFO GRID (inutilisée mais gardée)
   ═══════════════════════════════════════════ */
.document-info-grid {
  display: grid;
  grid-template-columns: repeat(2,1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}
.info-item {
  padding: 1rem 1.25rem;
  background: linear-gradient(135deg, rgba(6,182,212,.04), rgba(20,184,166,.04));
  border: 1px solid var(--doc-glass-border);
  border-radius: 12px;
}
.info-label {
  font-size: .72rem;
  color: var(--doc-muted);
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: .06em;
  margin-bottom: .3rem;
}
.info-value {
  font-size: 1rem;
  color: var(--doc-text);
  font-weight: 700;
}

/* ═══════════════════════════════════════════
   TAGS
   ═══════════════════════════════════════════ */
.doc-tags-label {
  font-size: .78rem;
  color: var(--doc-muted);
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  margin-bottom: .6rem;
}
.doc-tag {
  display: inline-flex;
  align-items: center;
  padding: .3rem .8rem;
  background: linear-gradient(135deg, var(--doc-cyan-10), rgba(20,184,166,.10));
  color: var(--doc-cyan);
  border: 1px solid var(--doc-cyan-20);
  border-radius: 50px;
  font-size: .8rem;
  font-weight: 600;
  transition: all .2s ease;
  cursor: default;
}
.doc-tag:hover {
  background: linear-gradient(135deg, var(--doc-cyan-20), rgba(20,184,166,.20));
  transform: translateY(-1px);
}

/* ═══════════════════════════════════════════
   SIDEBAR
   ═══════════════════════════════════════════ */
.document-sidebar {
  position: sticky;
  top: 1.5rem;
  height: fit-content;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* ═══════════════════════════════════════════
   PURCHASE CARD
   ═══════════════════════════════════════════ */
.purchase-card {
  background: var(--doc-glass-light);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-radius: var(--doc-radius);
  border: 1px solid var(--doc-glass-border);
  padding: 1.75rem;
  box-shadow:
    0 4px 6px rgba(0,0,0,.04),
    0 20px 48px rgba(6,182,212,.08),
    inset 0 1px 0 rgba(255,255,255,.9);
  position: relative;
  overflow: hidden;
}

/* Bord animé en haut de la card */
.purchase-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--doc-cyan), var(--doc-teal), var(--doc-cyan));
  background-size: 200% 100%;
  animation: shimmerLine 3s linear infinite;
}

/* Badge GRATUIT */
.doc-free-badge {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  padding: .85rem 1rem;
  background: linear-gradient(135deg, #10b981, #059669);
  color: #fff;
  border-radius: 12px;
  font-weight: 800;
  font-size: 1rem;
  letter-spacing: .05em;
  margin-bottom: 1.1rem;
  box-shadow: 0 4px 16px rgba(16,185,129,.3);
  animation: pulseGreen 2s ease-in-out infinite;
}
@keyframes pulseGreen {
  0%,100% { box-shadow: 0 4px 16px rgba(16,185,129,.3); }
  50%      { box-shadow: 0 4px 24px rgba(16,185,129,.5); }
}

/* ═══════════════════════════════════════════
   PRIX
   ═══════════════════════════════════════════ */
.price-section {
  margin-bottom: 1.5rem;
  text-align: center;
  padding: 1.25rem;
  background: linear-gradient(135deg, rgba(6,182,212,.05), rgba(20,184,166,.05));
  border-radius: 14px;
  border: 1px solid var(--doc-glass-border);
}
.price-current {
  font-size: 2.4rem;
  font-weight: 900;
  background: linear-gradient(135deg, var(--doc-cyan), var(--doc-teal));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1.1;
  margin-bottom: .35rem;
  letter-spacing: -.02em;
}
.price-old {
  font-size: 1rem;
  color: var(--doc-muted);
  text-decoration: line-through;
  margin-bottom: .25rem;
}
.discount-badge {
  display: inline-block;
  padding: .2rem .65rem;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  color: #92400e;
  border-radius: 50px;
  font-size: .78rem;
  font-weight: 800;
  margin-left: .4rem;
  border: 1px solid #fcd34d;
}

/* Note moyenne inline */
.doc-rating-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  margin: 1rem 0 1.25rem;
  padding: .7rem 1rem;
  background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(251,191,36,.08));
  border-radius: 10px;
  border: 1px solid rgba(245,158,11,.2);
}
.doc-rating-stars { color: #f59e0b; font-size: .9rem; }
.doc-rating-val   { font-weight: 800; color: var(--doc-text); }
.doc-rating-count { font-size: .8rem; color: var(--doc-muted); }

/* ═══════════════════════════════════════════
   BOUTONS
   ═══════════════════════════════════════════ */
.checkout-btn-doc {
  width: 100%;
  padding: 1.1rem 1.5rem;
  background: linear-gradient(135deg, var(--doc-cyan), var(--doc-teal));
  color: #fff;
  border: none;
  border-radius: 14px;
  font-weight: 800;
  font-size: 1rem;
  cursor: pointer;
  transition: all .3s cubic-bezier(.4,0,.2,1);
  margin-bottom: .65rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .65rem;
  box-shadow: 0 6px 20px rgba(6,182,212,.35);
  position: relative;
  overflow: hidden;
}
.checkout-btn-doc::after {
  content: '';
  position: absolute;
  top: 0; left: -100%;
  width: 60%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
  transition: left .5s ease;
}
.checkout-btn-doc:hover::after { left: 150%; }
.checkout-btn-doc:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(6,182,212,.5);
}

.purchase-btn {
  width: 100%;
  padding: .9rem 1.5rem;
  background: transparent;
  color: var(--doc-cyan);
  border: 2px solid var(--doc-cyan-30);
  border-radius: 14px;
  font-size: .95rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .3s ease;
  margin-bottom: .75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .65rem;
}
.purchase-btn:hover {
  background: var(--doc-cyan-10);
  border-color: var(--doc-cyan);
  transform: translateY(-2px);
}
.purchase-btn:disabled { opacity: .5; cursor: not-allowed; }

.download-btn {
  width: 100%;
  padding: 1.1rem 1.5rem;
  background: linear-gradient(135deg, #10b981, #059669);
  color: #fff;
  border: none;
  border-radius: 14px;
  font-size: 1rem;
  font-weight: 800;
  cursor: pointer;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .65rem;
  transition: all .3s ease;
  box-shadow: 0 6px 20px rgba(16,185,129,.3);
  position: relative;
  overflow: hidden;
}
.download-btn::after {
  content: '';
  position: absolute;
  top: 0; left: -100%;
  width: 60%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
  transition: left .5s ease;
}
.download-btn:hover::after { left: 150%; }
.download-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(16,185,129,.5);
  color: #fff;
}

.doc-security-note {
  text-align: center;
  color: var(--doc-muted);
  font-size: .8rem;
  margin-top: .5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .35rem;
}
.doc-security-note i { color: var(--doc-cyan); }

/* ═══════════════════════════════════════════
   WISHLIST BUTTON
   ═══════════════════════════════════════════ */
.wishlist-btn {
  width: 100%;
  padding: .8rem 1rem;
  border: 2px solid #e2e8f0;
  background: transparent;
  color: var(--doc-muted);
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all .3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  font-size: .9rem;
}
.wishlist-btn:hover {
  border-color: #fca5a5;
  background: #fff5f5;
  color: var(--doc-red);
}

/* ═══════════════════════════════════════════
   PARTAGE SOCIAL
   ═══════════════════════════════════════════ */
.doc-share-section {
  margin-top: 1.25rem;
  padding-top: 1.25rem;
  border-top: 1px solid rgba(6,182,212,.12);
}
.doc-share-label {
  font-weight: 700;
  margin-bottom: .65rem;
  color: var(--doc-text);
  font-size: .85rem;
  display: flex;
  align-items: center;
  gap: .4rem;
}
.doc-share-label i { color: var(--doc-cyan); }
.doc-share-buttons {
  display: grid;
  grid-template-columns: repeat(4,1fr);
  gap: .5rem;
}
.doc-share-btn {
  padding: .65rem;
  color: #fff;
  border-radius: 10px;
  text-align: center;
  text-decoration: none;
  font-size: .9rem;
  font-weight: 700;
  transition: all .25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.doc-share-btn:hover { transform: translateY(-3px); filter: brightness(1.1); }
.doc-share-fb  { background: #1877f2; }
.doc-share-tw  { background: #1da1f2; }
.doc-share-wa  { background: #25d366; }
.doc-share-li  { background: #0077b5; }

/* ═══════════════════════════════════════════
   RELATED SIDEBAR CARD
   ═══════════════════════════════════════════ */
.related-sidebar-card {
  background: var(--doc-glass-light);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-radius: var(--doc-radius);
  border: 1px solid var(--doc-glass-border);
  padding: 1.5rem;
  box-shadow:
    0 4px 6px rgba(0,0,0,.04),
    0 16px 40px rgba(6,182,212,.07),
    inset 0 1px 0 rgba(255,255,255,.9);
}
.related-sidebar-title {
  font-size: 1rem;
  font-weight: 800;
  color: var(--doc-text);
  margin-bottom: 1.1rem;
  display: flex;
  align-items: center;
  gap: .5rem;
  padding-bottom: .85rem;
  border-bottom: 1px solid rgba(6,182,212,.1);
}
.related-sidebar-title i { color: var(--doc-cyan); }
.related-sidebar-list { display: flex; flex-direction: column; gap: .65rem; }

.related-sidebar-item {
  display: flex;
  gap: .75rem;
  padding: .75rem;
  border-radius: 12px;
  text-decoration: none;
  transition: all .25s ease;
  border: 1px solid transparent;
  background: transparent;
}
.related-sidebar-item:hover {
  background: rgba(6,182,212,.06);
  border-color: var(--doc-cyan-20);
  transform: translateX(4px);
}

.related-sidebar-image {
  width: 58px; height: 58px;
  flex-shrink: 0;
  border-radius: 10px;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(6,182,212,.1), rgba(20,184,166,.1));
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid var(--doc-glass-border);
}
.related-sidebar-image img { width: 100%; height: 100%; object-fit: cover; }
.related-sidebar-placeholder { color: var(--doc-cyan); font-size: 1.4rem; }

.related-sidebar-content { flex: 1; min-width: 0; }
.related-sidebar-item-title {
  font-size: .83rem;
  font-weight: 700;
  color: var(--doc-text);
  margin-bottom: .3rem;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.related-sidebar-price {
  font-size: .83rem;
  font-weight: 800;
  background: linear-gradient(135deg, var(--doc-cyan), var(--doc-teal));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ═══════════════════════════════════════════
   RELATED SECTION BAS DE PAGE (gardée)
   ═══════════════════════════════════════════ */
.related-section {
  margin-top: 3rem;
  padding-top: 3rem;
  border-top: 1px solid rgba(6,182,212,.12);
}
.related-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--doc-text);
  margin-bottom: 1.5rem;
}
.related-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px,1fr));
  gap: 1.5rem;
}

/* ═══════════════════════════════════════════
   FLASH MESSAGES
   ═══════════════════════════════════════════ */
.flash-message {
  position: fixed;
  top: 84px; right: 20px;
  z-index: 10000;
  border-radius: 14px;
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  min-width: 320px;
  max-width: 480px;
  animation: slideInRight .4s ease-out;
  border: 1px solid transparent;
  backdrop-filter: blur(16px);
  box-shadow: 0 8px 32px rgba(0,0,0,.12);
}
@keyframes slideInRight {
  from { transform: translateX(420px); opacity: 0; }
  to   { transform: translateX(0);     opacity: 1; }
}
.flash-content { display: flex; align-items: center; gap: .75rem; flex: 1; }
.flash-content i { font-size: 1.2rem; flex-shrink: 0; }
.flash-content span { font-size: .92rem; font-weight: 600; line-height: 1.4; }
.flash-close {
  background: none; border: none; cursor: pointer;
  padding: .25rem; display: flex; align-items: center; justify-content: center;
  border-radius: 6px; transition: all .2s ease; flex-shrink: 0;
  opacity: .5;
}
.flash-close:hover { opacity: 1; }
.flash-success { background: #f0fdf4; border-color: rgba(16,185,129,.3); color: #065f46; }
.flash-success .flash-content i { color: var(--doc-green); }
.flash-info    { background: #eff6ff; border-color: rgba(6,182,212,.3);  color: #0c4a6e; }
.flash-info    .flash-content i { color: var(--doc-cyan); }
.flash-error   { background: #fef2f2; border-color: rgba(239,68,68,.3);  color: #991b1b; }
.flash-error   .flash-content i { color: var(--doc-red); }

/* ═══════════════════════════════════════════
   DARK MODE
   ═══════════════════════════════════════════ */
body.dark-mode .document-detail-page {
  background:
    radial-gradient(ellipse 80% 50% at 20% 0%, rgba(6,182,212,.06) 0%, transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 100%, rgba(20,184,166,.05) 0%, transparent 55%),
    linear-gradient(160deg, #060c1a 0%, #0a1628 50%, #060e18 100%) !important;
}
body.dark-mode .document-main,
body.dark-mode .purchase-card,
body.dark-mode .related-sidebar-card {
  background: rgba(15,23,42,.75);
  border-color: rgba(6,182,212,.2);
  box-shadow:
    0 4px 6px rgba(0,0,0,.3),
    0 20px 48px rgba(6,182,212,.08),
    inset 0 1px 0 rgba(255,255,255,.04);
}
body.dark-mode .document-title-large {
  background: linear-gradient(135deg, #e2e8f0 0%, #f8fafc 40%, #67e8f9 100%);
  -webkit-background-clip: text;
  background-clip: text;
}
body.dark-mode .document-category-badge {
  background: rgba(6,182,212,.15);
  color: #67e8f9;
  border-color: rgba(6,182,212,.3);
}
body.dark-mode .document-meta { border-bottom-color: rgba(6,182,212,.15); }
body.dark-mode .meta-item { color: rgba(255,255,255,.65); }
body.dark-mode .document-description { color: rgba(255,255,255,.82); }
body.dark-mode .info-item {
  background: rgba(6,182,212,.07);
  border-color: rgba(6,182,212,.2);
}
body.dark-mode .info-label { color: rgba(255,255,255,.5); }
body.dark-mode .info-value { color: #f1f5f9; }
body.dark-mode .doc-tag {
  background: rgba(6,182,212,.12);
  border-color: rgba(6,182,212,.25);
  color: #67e8f9;
}
body.dark-mode .price-section {
  background: rgba(6,182,212,.06);
  border-color: rgba(6,182,212,.15);
}
body.dark-mode .doc-security-note,
body.dark-mode .purchase-card p { color: rgba(255,255,255,.5); }
body.dark-mode .related-sidebar-title {
  color: #f1f5f9;
  border-bottom-color: rgba(6,182,212,.15);
}
body.dark-mode .related-sidebar-item-title { color: #e2e8f0; }
body.dark-mode .related-sidebar-item:hover { background: rgba(6,182,212,.08); }
body.dark-mode .related-sidebar-image { background: rgba(6,182,212,.1); }
body.dark-mode .related-section { border-top-color: rgba(6,182,212,.15); }
body.dark-mode .related-title { color: #f1f5f9; }
body.dark-mode .wishlist-btn { border-color: rgba(255,255,255,.12); color: rgba(255,255,255,.6); }
body.dark-mode .wishlist-btn:hover { border-color: rgba(239,68,68,.5); background: rgba(239,68,68,.1); color: #fca5a5; }
body.dark-mode .doc-share-label { color: #e2e8f0; }
body.dark-mode .doc-share-section { border-top-color: rgba(6,182,212,.15); }
body.dark-mode .purchase-btn { color: #67e8f9; border-color: rgba(6,182,212,.3); }
body.dark-mode .purchase-btn:hover { background: rgba(6,182,212,.12); border-color: rgba(6,182,212,.5); }
body.dark-mode .flash-success { background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.3); color: #6ee7b7; }
body.dark-mode .flash-info    { background: rgba(6,182,212,.12);  border-color: rgba(6,182,212,.3);  color: #67e8f9; }
body.dark-mode .flash-error   { background: rgba(239,68,68,.12);  border-color: rgba(239,68,68,.3);  color: #fca5a5; }
body.dark-mode .flash-close   { color: rgba(255,255,255,.5); }

/* ═══════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════ */
@media (max-width: 1024px) {
  .document-detail-container { grid-template-columns: 1fr; }
  .document-sidebar { position: static; }
  .doc-share-buttons { grid-template-columns: repeat(4,1fr); }
}
@media (max-width: 640px) {
  .document-detail-page { padding: 1.25rem .75rem 3rem; }
  .doc-body { padding: 1.5rem 1.25rem 2rem; }
  .document-cover-large { height: 240px; }
  .document-title-large { font-size: 1.4rem; }
  .flash-message { right: 10px; left: 10px; min-width: auto; }
  .doc-share-buttons { grid-template-columns: repeat(2,1fr); }
}

/* ═══════════════════════════════════════════
   ANIMATION DOCUMENT PURCHASE FORMS
   ═══════════════════════════════════════════ */
.document-purchase-forms { margin-bottom: 0; }
.document-purchase-forms + .document-purchase-forms { margin-top: .65rem; }
</style>
@endpush

@section('content')
{{-- Flash messages --}}
@if(session('success'))
<div class="flash-message flash-success" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif
@if(session('info'))
<div class="flash-message flash-info" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif
@if(session('error'))
<div class="flash-message flash-error" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif

<div class="document-detail-page">
    <div class="document-detail-container">

        {{-- ── COLONNE PRINCIPALE ── --}}
        <div class="document-main">

            {{-- Cover --}}
            @if($document->cover_image)
                <img src="{{ $documentImage }}"
                     alt="{{ $document->title }}" class="document-cover-large"
                     width="800" height="360" loading="eager" fetchpriority="high" decoding="async">
            @else
                <div class="document-cover-large doc-placeholder">
                    <i class="fas fa-file-{{ $document->file_extension === 'pdf' ? 'pdf' : ($document->file_extension === 'doc' || $document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                </div>
            @endif

            <div class="doc-cover-accent"></div>

            <div class="doc-body">
                {{-- Badge catégorie --}}
                <div class="document-category-badge">
                    <i class="fas fa-folder"></i> {{ $document->category->name }}
                </div>

                {{-- Titre --}}
                <h1 class="document-title-large">{{ $document->title }}</h1>

                {{-- Meta --}}
                <div class="document-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $document->author->name ?? 'Admin' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $document->published_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span>{{ number_format($document->views_count, 0, ',', ' ') }} vues</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ number_format($document->sales_count, 0, ',', ' ') }} ventes</span>
                    </div>
                </div>

                {{-- Description --}}
                @if($document->description)
                    <div class="document-description">
                        {!! nl2br(e($document->description)) !!}
                    </div>
                @endif

                {{-- Tags --}}
                @if($document->tags && count($document->tags) > 0)
                    <div style="margin-top: 1.75rem;">
                        <div class="doc-tags-label">Tags</div>
                        <div style="display: flex; flex-wrap: wrap; gap: .45rem;">
                            @foreach($document->tags as $tag)
                                <span class="doc-tag">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── SIDEBAR ── --}}
        <aside class="document-sidebar">
            <div class="purchase-card">

                {{-- Badge GRATUIT --}}
                @if($document->isFree())
                    <div class="doc-free-badge">
                        <i class="fas fa-gift"></i> GRATUIT
                    </div>
                @endif

                {{-- Prix --}}
                <div class="price-section">
                    @if($document->isFree())
                        <div class="price-current" style="font-size: 1.8rem;">
                            <i class="fas fa-gift"></i> Gratuit
                        </div>
                    @elseif($document->hasDiscount())
                        <div class="price-old">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                        <div class="price-current">
                            {{ number_format($document->discount_price, 0, ',', ' ') }} FCFA
                            <span class="discount-badge">-{{ $document->getDiscountPercentage() }}%</span>
                        </div>
                    @else
                        <div class="price-current">{{ number_format($document->price, 0, ',', ' ') }} FCFA</div>
                    @endif
                </div>

                {{-- Note --}}
                @if(isset($reviews) && $reviews->count() > 0)
                <div class="doc-rating-row">
                    <div class="doc-rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($document->average_rating) ? '' : '-o' }}"></i>
                        @endfor
                    </div>
                    <span class="doc-rating-val">{{ number_format($document->average_rating, 1) }}</span>
                    <span class="doc-rating-count">({{ $document->reviews_count }})</span>
                </div>
                @endif

                {{-- Actions --}}
                @if($document->isFree())
                    <a href="{{ route('documents.download-free', $document->id) }}" class="download-btn">
                        <i class="fas fa-download"></i> Télécharger gratuitement
                    </a>
                @elseif($userHasPurchased)
                    <a href="#" class="download-btn">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                    <p class="doc-security-note" style="margin-top: .75rem;">
                        <i class="fas fa-check-circle"></i> Vous avez déjà acheté ce document
                    </p>
                @else
                    <form action="{{ route('documents.cart.add', $document->id) }}" method="POST" class="document-purchase-forms">
                        @csrf
                        <input type="hidden" name="redirect" value="checkout">
                        <button type="submit" class="checkout-btn-doc">
                            <i class="fas fa-credit-card"></i> Procéder au paiement
                        </button>
                    </form>
                    <form action="{{ route('documents.cart.add', $document->id) }}" method="POST" class="document-purchase-forms">
                        @csrf
                        <button type="submit" class="purchase-btn">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                    </form>
                    <p class="doc-security-note">
                        <i class="fas fa-shield-alt"></i> Paiement 100 % sécurisé
                    </p>
                @endif

                {{-- Wishlist --}}
                @auth
                <div style="margin-top: 1rem;">
                    <button type="button"
                            onclick="toggleWishlist({{ $document->id }})"
                            id="wishlist-btn-{{ $document->id }}"
                            class="wishlist-btn"
                            style="{{ $inWishlist ? 'border-color:#ef4444;background:#fff5f5;color:#ef4444;' : '' }}">
                        <i class="fas fa-heart{{ $inWishlist ? '' : '-o' }}"></i>
                        <span id="wishlist-text-{{ $document->id }}">{{ $inWishlist ? 'Retirer de la wishlist' : 'Ajouter à la wishlist' }}</span>
                    </button>
                </div>
                @endauth

                {{-- Partage --}}
                <div class="doc-share-section">
                    <p class="doc-share-label"><i class="fas fa-share-alt"></i> Partager</p>
                    <div class="doc-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="doc-share-btn doc-share-fb" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($document->title) }}" target="_blank" class="doc-share-btn doc-share-tw" title="Twitter/X">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($document->title . ' - ' . url()->current()) }}" target="_blank" class="doc-share-btn doc-share-wa" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="doc-share-btn doc-share-li" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Documents similaires --}}
            @if($relatedDocuments->count() > 0)
            <div class="related-sidebar-card">
                <h3 class="related-sidebar-title">
                    <i class="fas fa-star"></i> Les nouveautés
                </h3>
                <div class="related-sidebar-list">
                    @foreach($relatedDocuments as $related)
                    <a href="{{ route('documents.show', $related->slug) }}" class="related-sidebar-item">
                        <div class="related-sidebar-image">
                            @if($related->cover_image)
                                @if($related->cover_type === 'internal')
                                    <img src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $related->id]) }}"
                                         alt="{{ $related->title }}" width="58" height="58" loading="lazy" decoding="async">
                                @else
                                    <img src="{{ $related->cover_image }}" alt="{{ $related->title }}" width="58" height="58" loading="lazy" decoding="async">
                                @endif
                            @else
                                <div class="related-sidebar-placeholder">
                                    <i class="fas fa-file-{{ $related->file_extension === 'pdf' ? 'pdf' : ($related->file_extension === 'doc' || $related->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-sidebar-content">
                            <h4 class="related-sidebar-item-title">{{ \Illuminate\Support\Str::limit($related->title, 50) }}</h4>
                            <div class="related-sidebar-price">
                                {{ number_format($related->hasDiscount() ? $related->discount_price : $related->price, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.flash-message').forEach(function(msg) {
            setTimeout(function() {
                msg.style.animation = 'slideOutRight .4s ease-out forwards';
                setTimeout(function() { msg.remove(); }, 400);
            }, 5000);
        });
    });

    const style = document.createElement('style');
    style.textContent = `@keyframes slideOutRight{from{transform:translateX(0);opacity:1}to{transform:translateX(420px);opacity:0}}`;
    document.head.appendChild(style);

    // Système de notation étoiles
    (function() {
        function initStarRating() {
            document.querySelectorAll('.star-rating-container').forEach(function(container) {
                if (container.dataset.initialized === 'true') return;
                container.dataset.initialized = 'true';
                const stars = container.querySelectorAll('.rating-star');
                const radios = container.querySelectorAll('.rating-radio');
                if (!stars.length || !radios.length) return;
                let selectedRating = 0, hoverRating = 0;
                function updateDisplay() {
                    const r = hoverRating || selectedRating;
                    stars.forEach(function(star) {
                        const v = parseInt(star.dataset.rating);
                        const icon = star.querySelector('i');
                        star.style.color = v <= r ? '#f59e0b' : '#e2e8f0';
                        star.style.transform = v <= r ? 'scale(1.15)' : 'scale(1)';
                        if (icon) icon.className = v <= r ? 'fas fa-star' : 'far fa-star';
                    });
                }
                stars.forEach(function(star) {
                    const v = parseInt(star.dataset.rating);
                    const radio = document.getElementById(star.dataset.for);
                    if (!radio) return;
                    star.addEventListener('click', function(e) {
                        e.preventDefault(); e.stopPropagation();
                        radio.checked = true; selectedRating = v; hoverRating = 0;
                        container.dataset.rating = selectedRating; updateDisplay();
                        radio.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                    star.addEventListener('mouseenter', function() { hoverRating = v; updateDisplay(); });
                    star.addEventListener('mouseleave', function() { hoverRating = 0; updateDisplay(); });
                });
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        selectedRating = parseInt(this.value); hoverRating = 0;
                        container.dataset.rating = selectedRating; updateDisplay();
                    });
                });
                updateDisplay();
            });
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initStarRating);
        else initStarRating();
        setTimeout(initStarRating, 500);
    })();

    // Toggle Wishlist
    function toggleWishlist(documentId) {
        const btn  = document.getElementById('wishlist-btn-' + documentId);
        const text = document.getElementById('wishlist-text-' + documentId);
        const icon = btn.querySelector('i');
        btn.disabled = true; btn.style.opacity = '.6';
        fetch('{{ route("wishlist.toggle", ":id") }}'.replace(':id', documentId), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (data.action === 'added') {
                    btn.style.cssText += 'border-color:#ef4444;background:#fff5f5;color:#ef4444;';
                    icon.className = 'fas fa-heart';
                    text.textContent = 'Retirer de la wishlist';
                } else {
                    btn.style.borderColor = ''; btn.style.background = ''; btn.style.color = '';
                    icon.className = 'fas fa-heart-o';
                    text.textContent = 'Ajouter à la wishlist';
                }
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Erreur', 'error');
            }
        })
        .catch(() => showNotification('Erreur lors de l\'opération', 'error'))
        .finally(() => { btn.disabled = false; btn.style.opacity = '1'; });
    }

    function showNotification(message, type) {
        const n = document.createElement('div');
        n.className = 'flash-message flash-' + (type === 'success' ? 'success' : 'error');
        n.innerHTML = `<div class="flash-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${message}</span></div><button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
        document.body.appendChild(n);
        setTimeout(() => { n.style.animation = 'slideOutRight .4s ease-out forwards'; setTimeout(() => n.remove(), 400); }, 3000);
    }
</script>
@endpush
