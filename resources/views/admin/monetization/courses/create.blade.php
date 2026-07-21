@extends('admin.layout')

@section('title', 'Créer un Cours Payant - Admin')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    html { scroll-behavior: smooth; }

    .cf-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 1.25rem;
        padding: 1.75rem;
        scroll-margin-top: 100px;
        transition: border-color 0.3s ease;
    }
    .cf-card:focus-within { border-color: rgba(6, 182, 212, 0.45); }
    body.light-mode .cf-card {
        background: rgba(255, 255, 255, 0.85);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .cf-title { color: #fff; font-weight: 800; font-size: 1.05rem; }
    body.light-mode .cf-title { color: #1e293b; }

    .cf-label { display: block; color: rgba(255,255,255,0.85); font-weight: 600; font-size: 0.85rem; margin-bottom: 0.4rem; }
    body.light-mode .cf-label { color: #334155; }

    .cf-hint { color: rgba(255,255,255,0.45); font-size: 0.78rem; margin-top: 0.35rem; }
    body.light-mode .cf-hint { color: #64748b; }

    .cf-error { color: #f87171; font-size: 0.78rem; margin-top: 0.35rem; display: flex; align-items: center; gap: 0.3rem; }

    .cf-tab-btn { padding: 0.5rem 1rem; border-radius: 0.6rem; font-size: 0.82rem; font-weight: 700; transition: all 0.2s ease; color: rgba(255,255,255,0.5); }
    .cf-tab-btn.active { background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #04141a; }
    .cf-tab-btn:not(.active):hover { color: #06b6d4; }
    body.light-mode .cf-tab-btn { color: #64748b; }

    .cf-tabs-wrap { background: rgba(0,0,0,0.25); border: 1px solid rgba(6,182,212,0.15); border-radius: 0.8rem; padding: 0.3rem; display: inline-flex; gap: 0.25rem; }
    body.light-mode .cf-tabs-wrap { background: rgba(6,182,212,0.06); }

    .cf-dropzone {
        border: 2px dashed rgba(6, 182, 212, 0.35);
        border-radius: 1rem;
        padding: 1.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: rgba(6, 182, 212, 0.04);
    }
    .cf-dropzone:hover, .cf-dropzone.cf-drag { border-color: #06b6d4; background: rgba(6, 182, 212, 0.1); }
    body.light-mode .cf-dropzone { background: rgba(6,182,212,0.04); }

    .cf-pill { padding: 0.65rem 1.1rem; border-radius: 0.75rem; border: 2px solid rgba(6,182,212,0.2); font-weight: 700; font-size: 0.85rem; color: rgba(255,255,255,0.6); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; }
    .cf-pill:hover { border-color: rgba(6,182,212,0.5); }
    body.light-mode .cf-pill { color: #475569; }

    .cf-pill.active-draft { background: rgba(148,163,184,0.18); border-color: #94a3b8; color: #cbd5e1; }
    .cf-pill.active-published { background: rgba(16,185,129,0.18); border-color: #10b981; color: #10b981; }
    .cf-pill.active-archived { background: rgba(148,163,184,0.12); border-color: #64748b; color: #94a3b8; }

    .cf-tag-row { display: flex; gap: 0.6rem; align-items: center; margin-bottom: 0.6rem; }
    .cf-tag-index { width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background: rgba(6,182,212,0.15); color: #06b6d4; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; }

    .cf-remove-btn { width: 2.5rem; height: 2.5rem; border-radius: 0.6rem; background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.3); cursor: pointer; flex-shrink: 0; transition: all 0.2s ease; }
    .cf-remove-btn:hover { background: rgba(239,68,68,0.22); }

    .cf-add-btn { padding: 0.6rem 1.1rem; border-radius: 0.7rem; background: rgba(16,185,129,0.12); border: 2px solid rgba(16,185,129,0.4); color: #10b981; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease; }
    .cf-add-btn:hover { background: rgba(16,185,129,0.22); transform: translateY(-1px); }

    .cf-preview-card {
        background: linear-gradient(160deg, rgba(15,23,42,0.9), rgba(6,182,212,0.06));
        border: 1px solid rgba(6,182,212,0.25);
        border-radius: 1.25rem;
        overflow: hidden;
    }
    body.light-mode .cf-preview-card { background: linear-gradient(160deg, #ffffff, rgba(6,182,212,0.05)); border-color: rgba(6,182,212,0.3); }

    .cf-preview-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; background: rgba(6,182,212,0.08); display: flex; align-items: center; justify-content: center; color: rgba(6,182,212,0.5); }

    .cf-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.02em; }

    .cf-sticky-actions {
        position: sticky;
        bottom: 0;
        margin-top: 2rem;
        padding: 1.1rem 1.5rem;
        background: rgba(10, 10, 26, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 1.25rem;
        display: flex;
        gap: 1rem;
        z-index: 10;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.3);
    }
    body.light-mode .cf-sticky-actions { background: rgba(255,255,255,0.92); box-shadow: 0 -10px 30px rgba(0,0,0,0.08); }

    .cf-quicknav a {
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        border: 1px solid rgba(6,182,212,0.25);
        color: rgba(255,255,255,0.6);
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .cf-quicknav a:hover { border-color: #06b6d4; color: #06b6d4; background: rgba(6,182,212,0.08); }
    body.light-mode .cf-quicknav a { color: #475569; }
</style>

<div x-data="courseForm({
        title: @js(old('title', '')),
        slug: @js(old('slug', '')),
        slugTouched: {{ old('slug') ? 'true' : 'false' }},
        coverType: @js(old('cover_type', 'internal')),
        coverImageUrl: @js(old('cover_image_url', '')),
        price: {{ old('price') !== null && old('price') !== '' ? (float) old('price') : 'null' }},
        discountPrice: {{ old('discount_price') !== null && old('discount_price') !== '' ? (float) old('discount_price') : 'null' }},
        currency: @js(old('currency', 'XOF')),
        status: @js(old('status', 'draft')),
        durationHours: {{ old('duration_hours') !== null && old('duration_hours') !== '' ? (int) old('duration_hours') : 'null' }},
        learnItems: @js(old('what_you_learn') ?: ['']),
        reqItems: @js(old('requirements') ?: [''])
     })" x-cloak>

    <!-- Header -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#06b6d4;">Monétisation / Cours Payants</p>
            <h1 class="text-3xl md:text-4xl font-black mt-1" style="color:white;">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-teal-400">Créer un Cours</span>
            </h1>
            <p class="mt-1" style="color: rgba(255,255,255,0.6);">Renseignez les informations ci-dessous pour publier un nouveau cours payant.</p>
        </div>
        <a href="{{ route('admin.monetization.courses.index') }}" class="cf-pill">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Quick nav -->
    <div class="cf-quicknav flex gap-2 overflow-x-auto pb-2 mb-6">
        <a href="#section-infos"><i class="fas fa-info-circle mr-1"></i>Informations</a>
        <a href="#section-image"><i class="fas fa-image mr-1"></i>Image</a>
        <a href="#section-prix"><i class="fas fa-tag mr-1"></i>Tarification</a>
        <a href="#section-params"><i class="fas fa-sliders-h mr-1"></i>Paramètres</a>
        <a href="#section-learn"><i class="fas fa-check-circle mr-1"></i>Objectifs</a>
        <a href="#section-req"><i class="fas fa-list-check mr-1"></i>Prérequis</a>
    </div>

    @if($errors->any())
    <div class="cf-card mb-6" style="border-color: rgba(239,68,68,0.4); background: rgba(239,68,68,0.08);">
        <p style="color:#f87171; font-weight:700;"><i class="fas fa-triangle-exclamation mr-2"></i>Merci de corriger les erreurs suivantes :</p>
        <ul class="mt-2 ml-6 list-disc" style="color:#f87171; font-size:0.85rem;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.monetization.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Main column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Informations générales -->
                <section id="section-infos" class="cf-card">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                        <h3 class="cf-title"><i class="fas fa-info-circle mr-2" style="color:#06b6d4;"></i>Informations Générales</h3>
                        <div class="cf-tabs-wrap">
                            <button type="button" class="cf-tab-btn" :class="langTab==='default' && 'active'" @click="langTab='default'">Par défaut</button>
                            <button type="button" class="cf-tab-btn" :class="langTab==='fr' && 'active'" @click="langTab='fr'">🇫🇷 Français</button>
                            <button type="button" class="cf-tab-btn" :class="langTab==='en' && 'active'" @click="langTab='en'">🇬🇧 English</button>
                        </div>
                    </div>

                    <!-- Tab: Default -->
                    <div x-show="langTab==='default'" class="space-y-4">
                        <div>
                            <label class="cf-label">Titre du Cours <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="title" x-model="title" @input="autoSlug()" required class="input-admin" placeholder="Ex: Formation Complète Laravel">
                            <p class="cf-hint">Titre par défaut utilisé si les traductions ne sont pas disponibles.</p>
                            @error('title')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Slug (URL) <span class="cf-hint" style="margin:0;">— généré automatiquement</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="slug" x-model="slug" @input="slugTouched = true" class="input-admin" placeholder="formation-complete-laravel">
                                <button type="button" @click="regenerateSlug()" class="cf-pill" title="Régénérer depuis le titre"><i class="fas fa-rotate"></i></button>
                            </div>
                            <p class="cf-hint"><i class="fas fa-link mr-1"></i>/monetization/courses/<span x-text="slug || '...'" style="color:#06b6d4;"></span></p>
                            @error('slug')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Description <span class="cf-hint" style="margin:0;" x-text="(description?.length || 0) + '/1000'"></span></label>
                            <textarea name="description" x-model="description" maxlength="1000" rows="4" class="input-admin" placeholder="Description courte du cours..."></textarea>
                            @error('description')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Contenu Complet</label>
                            <textarea name="content" rows="8" class="input-admin" placeholder="Contenu détaillé du cours...">{{ old('content') }}</textarea>
                            @error('content')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Tab: FR -->
                    <div x-show="langTab==='fr'" class="space-y-4">
                        <div>
                            <label class="cf-label">Titre (Français)</label>
                            <input type="text" name="title_fr" value="{{ old('title_fr') }}" class="input-admin" placeholder="Ex: Formation Complète Laravel">
                            @error('title_fr')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Description (Français)</label>
                            <textarea name="description_fr" rows="4" class="input-admin" placeholder="Description courte du cours...">{{ old('description_fr') }}</textarea>
                            @error('description_fr')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Contenu Complet (Français)</label>
                            <textarea name="content_fr" rows="8" class="input-admin" placeholder="Contenu détaillé du cours...">{{ old('content_fr') }}</textarea>
                            @error('content_fr')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Tab: EN -->
                    <div x-show="langTab==='en'" class="space-y-4">
                        <div>
                            <label class="cf-label">Title (English)</label>
                            <input type="text" name="title_en" value="{{ old('title_en') }}" class="input-admin" placeholder="Ex: Complete Laravel Course">
                            @error('title_en')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Description (English)</label>
                            <textarea name="description_en" rows="4" class="input-admin" placeholder="Short course description...">{{ old('description_en') }}</textarea>
                            @error('description_en')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Full Content (English)</label>
                            <textarea name="content_en" rows="8" class="input-admin" placeholder="Detailed course content...">{{ old('content_en') }}</textarea>
                            @error('content_en')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <!-- Image -->
                <section id="section-image" class="cf-card">
                    <h3 class="cf-title mb-5"><i class="fas fa-image mr-2" style="color:#06b6d4;"></i>Image de Couverture</h3>

                    <div class="flex gap-3 mb-5">
                        <button type="button" class="cf-pill" :class="coverType==='internal' && 'active-published'" @click="coverType='internal'">
                            <i class="fas fa-upload"></i> Upload interne
                        </button>
                        <button type="button" class="cf-pill" :class="coverType==='external' && 'active-published'" @click="coverType='external'">
                            <i class="fas fa-link"></i> URL externe
                        </button>
                    </div>
                    <select name="cover_type" x-model="coverType" class="sr-only" required>
                        <option value="internal">Interne (upload)</option>
                        <option value="external">Externe (URL)</option>
                    </select>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                        <div>
                            <!-- Internal -->
                            <div x-show="coverType==='internal'">
                                <div class="cf-dropzone" :class="dragOver && 'cf-drag'"
                                     @click="$refs.coverFile.click()"
                                     @dragover.prevent="dragOver = true"
                                     @dragleave.prevent="dragOver = false"
                                     @drop.prevent="onDrop($event)">
                                    <i class="fas fa-cloud-arrow-up text-3xl mb-2" style="color:#06b6d4;"></i>
                                    <p style="color:rgba(255,255,255,0.75); font-weight:600;">Glissez une image ici ou cliquez pour parcourir</p>
                                    <p class="cf-hint">JPEG, PNG, JPG, GIF, WEBP — max 2MB</p>
                                </div>
                                <input type="file" name="cover_image_file" x-ref="coverFile" accept="image/*" class="sr-only" @change="onFileChange($event)">
                                @error('cover_image_file')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                            </div>
                            <!-- External -->
                            <div x-show="coverType==='external'">
                                <label class="cf-label">URL de l'image</label>
                                <input type="url" name="cover_image_url" x-model="coverImageUrl" class="input-admin" placeholder="https://example.com/image.jpg">
                                <p class="cf-hint">L'URL complète doit commencer par http:// ou https://</p>
                                @error('cover_image_url')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="cf-label">Aperçu</label>
                            <div class="cf-preview-img rounded-xl overflow-hidden">
                                <template x-if="previewSrc">
                                    <img loading="lazy" :src="previewSrc" class="w-full h-full object-cover" alt="Aperçu">
                                </template>
                                <template x-if="!previewSrc">
                                    <i class="fas fa-image text-4xl"></i>
                                </template>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Prix -->
                <section id="section-prix" class="cf-card">
                    <h3 class="cf-title mb-5"><i class="fas fa-tag mr-2" style="color:#06b6d4;"></i>Prix et Réductions</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="cf-label">Prix <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="price" x-model.number="price" required min="0" step="100" class="input-admin">
                            @error('price')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Devise <span style="color:#ef4444;">*</span></label>
                            <select name="currency" x-model="currency" required class="input-admin">
                                <option value="XOF">XOF (FCFA)</option>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 p-5 rounded-xl" style="background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.3);">
                        <div class="flex items-center justify-between mb-4">
                            <h4 style="color:#fbbf24; font-weight:700;"><i class="fas fa-percent mr-2"></i>Réduction (Optionnel)</h4>
                            <span x-show="discountPercent() !== null" class="cf-badge" style="background: rgba(251,191,36,0.2); color:#fbbf24;">
                                -<span x-text="discountPercent()"></span>%
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="cf-label" style="font-size:0.8rem;">Prix Réduit</label>
                                <input type="number" name="discount_price" x-model.number="discountPrice" min="0" step="100" class="input-admin">
                            </div>
                            <div>
                                <label class="cf-label" style="font-size:0.8rem;">Date Début</label>
                                <input type="date" name="discount_start" value="{{ old('discount_start') }}" class="input-admin">
                            </div>
                            <div>
                                <label class="cf-label" style="font-size:0.8rem;">Date Fin</label>
                                <input type="date" name="discount_end" value="{{ old('discount_end') }}" class="input-admin">
                                @error('discount_end')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <p class="cf-hint mt-3" x-show="discountPercent() !== null">
                            Prix final affiché : <strong style="color:#10b981;" x-text="discountPrice + ' ' + currency"></strong>
                            <span style="text-decoration: line-through; opacity:0.6;" x-text="price + ' ' + currency"></span>
                        </p>
                    </div>
                </section>

                <!-- Paramètres -->
                <section id="section-params" class="cf-card">
                    <h3 class="cf-title mb-5"><i class="fas fa-sliders-h mr-2" style="color:#06b6d4;"></i>Informations Complémentaires</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="cf-label">Durée (heures)</label>
                            <input type="number" name="duration_hours" x-model.number="durationHours" min="1" class="input-admin" placeholder="Ex: 40">
                            @error('duration_hours')<p class="cf-error"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="cf-label">Statut <span style="color:#ef4444;">*</span></label>
                            <select name="status" x-model="status" class="sr-only" required>
                                <option value="draft">Brouillon</option>
                                <option value="published">Publié</option>
                                <option value="archived">Archivé</option>
                            </select>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="cf-pill" :class="status==='draft' && 'active-draft'" @click="status='draft'"><i class="fas fa-pen"></i> Brouillon</button>
                                <button type="button" class="cf-pill" :class="status==='published' && 'active-published'" @click="status='published'"><i class="fas fa-check-circle"></i> Publié</button>
                                <button type="button" class="cf-pill" :class="status==='archived' && 'active-archived'" @click="status='archived'"><i class="fas fa-box-archive"></i> Archivé</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Ce que vous apprendrez -->
                <section id="section-learn" class="cf-card">
                    <h3 class="cf-title mb-5"><i class="fas fa-check-circle mr-2" style="color:#06b6d4;"></i>Ce que vous apprendrez</h3>
                    <template x-for="(item, index) in learnItems" :key="'learn-'+index">
                        <div class="cf-tag-row">
                            <div class="cf-tag-index" x-text="index + 1"></div>
                            <input type="text" name="what_you_learn[]" x-model="learnItems[index]" @keydown.enter.prevent="addLearnItem()" class="input-admin" placeholder="Ex: Créer des applications Laravel">
                            <button type="button" class="cf-remove-btn" @click="removeLearnItem(index)"><i class="fas fa-times"></i></button>
                        </div>
                    </template>
                    <button type="button" class="cf-add-btn" @click="addLearnItem()"><i class="fas fa-plus mr-1"></i>Ajouter un point</button>
                </section>

                <!-- Prérequis -->
                <section id="section-req" class="cf-card">
                    <h3 class="cf-title mb-5"><i class="fas fa-list-check mr-2" style="color:#06b6d4;"></i>Prérequis</h3>
                    <template x-for="(item, index) in reqItems" :key="'req-'+index">
                        <div class="cf-tag-row">
                            <div class="cf-tag-index" x-text="index + 1"></div>
                            <input type="text" name="requirements[]" x-model="reqItems[index]" @keydown.enter.prevent="addReqItem()" class="input-admin" placeholder="Ex: Connaissances de base en PHP">
                            <button type="button" class="cf-remove-btn" @click="removeReqItem(index)"><i class="fas fa-times"></i></button>
                        </div>
                    </template>
                    <button type="button" class="cf-add-btn" @click="addReqItem()"><i class="fas fa-plus mr-1"></i>Ajouter un prérequis</button>
                </section>

                <!-- Sticky actions -->
                <div class="cf-sticky-actions">
                    <button type="submit" class="flex-1 py-3 rounded-xl font-bold text-lg" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color:#04141a; box-shadow: 0 4px 15px rgba(6,182,212,0.3);">
                        <i class="fas fa-save mr-2"></i>Créer le Cours
                    </button>
                    <a href="{{ route('admin.monetization.courses.index') }}" class="cf-pill px-6">Annuler</a>
                </div>
            </div>

            <!-- Sidebar preview -->
            <div class="lg:col-span-1">
                <div class="sticky space-y-5" style="top: 6.5rem;">
                    <div class="cf-preview-card">
                        <div class="cf-preview-img">
                            <template x-if="previewSrc">
                                <img loading="lazy" :src="previewSrc" class="w-full h-full object-cover" alt="Aperçu du cours">
                            </template>
                            <template x-if="!previewSrc">
                                <i class="fas fa-graduation-cap text-4xl"></i>
                            </template>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="cf-badge" :style="status==='published' ? 'background:rgba(16,185,129,0.2);color:#10b981;' : (status==='archived' ? 'background:rgba(100,116,139,0.2);color:#94a3b8;' : 'background:rgba(148,163,184,0.2);color:#cbd5e1;')">
                                    <i class="fas fa-circle" style="font-size:0.5rem;"></i>
                                    <span x-text="status==='published' ? 'Publié' : (status==='archived' ? 'Archivé' : 'Brouillon')"></span>
                                </span>
                                <span class="cf-badge" style="background: rgba(6,182,212,0.15); color:#06b6d4;" x-show="durationHours">
                                    <i class="fas fa-clock"></i> <span x-text="durationHours"></span>h
                                </span>
                            </div>
                            <h4 style="color:white; font-weight:800; font-size:1.1rem; line-height:1.3;" x-text="title || 'Titre du cours...'"></h4>
                            <p class="mt-2 text-sm" style="color:rgba(255,255,255,0.5); display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;" x-text="description || 'La description apparaîtra ici.'"></p>
                            <div class="mt-4 flex items-baseline gap-2">
                                <template x-if="discountPercent() !== null">
                                    <span style="text-decoration: line-through; color:rgba(255,255,255,0.4); font-size:0.9rem;" x-text="price + ' ' + currency"></span>
                                </template>
                                <span style="font-weight:900; font-size:1.4rem;" class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-teal-400" x-text="(discountPercent() !== null ? discountPrice : price) + ' ' + currency"></span>
                            </div>
                        </div>
                    </div>

                    <div class="cf-card">
                        <h4 class="cf-title text-sm mb-3"><i class="fas fa-lightbulb mr-2" style="color:#fbbf24;"></i>Conseils</h4>
                        <ul class="space-y-2 text-sm" style="color: rgba(255,255,255,0.55);">
                            <li><i class="fas fa-check mr-2" style="color:#10b981;"></i>Ajoutez une image 16:9 pour un rendu optimal.</li>
                            <li><i class="fas fa-check mr-2" style="color:#10b981;"></i>Renseignez les traductions FR/EN pour toucher plus de monde.</li>
                            <li><i class="fas fa-check mr-2" style="color:#10b981;"></i>Passez en "Brouillon" pour préparer le cours avant publication.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function courseForm(opts) {
    return {
        title: opts.title,
        slug: opts.slug,
        slugTouched: opts.slugTouched,
        description: @json(old('description', '')),
        coverType: opts.coverType,
        coverImageUrl: opts.coverImageUrl,
        previewSrc: opts.coverType === 'external' ? opts.coverImageUrl : null,
        dragOver: false,
        price: opts.price ?? 0,
        discountPrice: opts.discountPrice,
        currency: opts.currency,
        status: opts.status,
        durationHours: opts.durationHours,
        learnItems: opts.learnItems.length ? opts.learnItems : [''],
        reqItems: opts.reqItems.length ? opts.reqItems : [''],
        langTab: 'default',

        slugify(text) {
            return (text || '')
                .toString()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        },
        autoSlug() {
            if (!this.slugTouched) {
                this.slug = this.slugify(this.title);
            }
        },
        regenerateSlug() {
            this.slug = this.slugify(this.title);
            this.slugTouched = false;
        },
        discountPercent() {
            if (this.price > 0 && this.discountPrice > 0 && this.discountPrice < this.price) {
                return Math.round((1 - (this.discountPrice / this.price)) * 100);
            }
            return null;
        },
        onFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => { this.previewSrc = e.target.result; };
            reader.readAsDataURL(file);
        },
        onDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;
            this.$refs.coverFile.files = event.dataTransfer.files;
            this.onFileChange({ target: { files: [file] } });
        },
        addLearnItem() { this.learnItems.push(''); },
        removeLearnItem(index) { if (this.learnItems.length > 1) this.learnItems.splice(index, 1); },
        addReqItem() { this.reqItems.push(''); },
        removeReqItem(index) { if (this.reqItems.length > 1) this.reqItems.splice(index, 1); },

        init() {
            this.$watch('coverImageUrl', (val) => {
                if (this.coverType === 'external') this.previewSrc = val;
            });
            this.$watch('coverType', (val) => {
                this.previewSrc = val === 'external' ? this.coverImageUrl : this.previewSrc;
            });
        }
    };
}
</script>
@endsection
