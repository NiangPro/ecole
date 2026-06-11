@php
    $isEdit = $epreuve && $epreuve->exists;
    $oldType = old('type', $epreuve?->type ?? 'epreuve');
    $oldExam = old('exam', $epreuve?->exam ?? '');
    $typeIcons = [
        'epreuve' => 'fa-file-lines',
        'corrige' => 'fa-circle-check',
        'epreuve_corrige' => 'fa-file-circle-check',
        'examen_blanc' => 'fa-stopwatch',
        'devoir' => 'fa-pen-to-square',
        'composition' => 'fa-pen-ruler',
    ];
@endphp

@section('styles')
<style>
.ep-form { --ep-accent: #10b981; --ep-accent2: #06b6d4; }
.ep-form * { box-sizing: border-box; }

/* ---- Cards ---- */
.ep-card {
    background: linear-gradient(160deg, rgba(17, 24, 39, 0.85), rgba(15, 23, 42, 0.95));
    border: 1px solid rgba(148, 163, 184, 0.12);
    border-radius: 20px;
    padding: 1.6rem;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(12px);
}
.ep-card::before {
    content: '';
    position: absolute; inset: 0 0 auto 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(16,185,129,0.5), rgba(6,182,212,0.5), transparent);
}
.ep-card-head { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1.4rem; }
.ep-step {
    width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center;
    border-radius: 11px; font-weight: 800; font-size: 0.9rem; color: #022c22;
    background: linear-gradient(135deg, #34d399, #06b6d4);
    box-shadow: 0 4px 14px rgba(16,185,129,0.35);
}
.ep-card-title { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; }
.ep-card-sub { font-size: 0.8rem; color: #64748b; margin-top: 1px; }

/* ---- Inputs ---- */
.ep-label { display: block; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; color: #94a3b8; margin-bottom: 0.45rem; }
.ep-input, .ep-select, .ep-textarea {
    width: 100%; padding: 0.7rem 1rem; font-size: 0.92rem; color: #f1f5f9;
    background: rgba(2, 6, 23, 0.55);
    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 12px;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    appearance: none;
}
.ep-input::placeholder, .ep-textarea::placeholder { color: #475569; }
.ep-input:focus, .ep-select:focus, .ep-textarea:focus {
    outline: none; border-color: rgba(16,185,129,0.6);
    box-shadow: 0 0 0 4px rgba(16,185,129,0.12);
    background: rgba(2, 6, 23, 0.8);
}
.ep-select-wrap { position: relative; }
.ep-select-wrap::after {
    content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
    font-size: 0.7rem; color: #64748b; pointer-events: none;
}
.ep-hint { font-size: 0.74rem; color: #64748b; margin-top: 0.4rem; line-height: 1.5; }
.ep-error { font-size: 0.78rem; color: #f87171; margin-top: 0.35rem; }

/* ---- Pills (radios) ---- */
.ep-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.ep-pill { position: relative; }
.ep-pill input { position: absolute; opacity: 0; inset: 0; cursor: pointer; }
.ep-pill span {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600;
    color: #94a3b8; background: rgba(2,6,23,0.5);
    border: 1px solid rgba(148,163,184,0.18);
    cursor: pointer; transition: all 0.18s; user-select: none;
}
.ep-pill:hover span { border-color: rgba(16,185,129,0.4); color: #cbd5e1; }
.ep-pill input:checked + span {
    color: #022c22; font-weight: 700;
    background: linear-gradient(135deg, #34d399, #2dd4bf);
    border-color: transparent;
    box-shadow: 0 4px 16px rgba(16,185,129,0.35);
}
.ep-pill input:focus-visible + span { box-shadow: 0 0 0 4px rgba(16,185,129,0.25); }

/* ---- Dropzone ---- */
.ep-drop {
    position: relative; border-radius: 16px; padding: 1.8rem 1.2rem;
    border: 2px dashed rgba(148,163,184,0.25);
    background: rgba(2,6,23,0.35);
    text-align: center; cursor: pointer; transition: all 0.2s;
}
.ep-drop:hover, .ep-drop.is-drag {
    border-color: rgba(16,185,129,0.65);
    background: rgba(16,185,129,0.06);
    transform: translateY(-1px);
}
.ep-drop input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.ep-drop-icon {
    width: 52px; height: 52px; margin: 0 auto 0.7rem; display: grid; place-items: center;
    border-radius: 16px; font-size: 1.3rem; color: #34d399;
    background: rgba(16,185,129,0.12);
    transition: transform 0.2s;
}
.ep-drop.is-drag .ep-drop-icon { transform: scale(1.12); }
.ep-drop--corrige .ep-drop-icon { color: #fbbf24; background: rgba(251,191,36,0.12); }
.ep-drop-label { font-weight: 700; font-size: 0.92rem; color: #e2e8f0; }
.ep-drop-label em { color: #34d399; font-style: normal; }
.ep-drop--corrige .ep-drop-label em { color: #fbbf24; }
.ep-drop-sub { font-size: 0.75rem; color: #64748b; margin-top: 0.3rem; }
.ep-file-chip {
    display: none; align-items: center; gap: 0.7rem; margin-top: 0.9rem;
    padding: 0.6rem 0.9rem; border-radius: 12px; text-align: left;
    background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
    animation: ep-pop 0.25s ease;
}
.ep-file-chip.is-visible { display: flex; }
.ep-file-chip i { color: #f87171; font-size: 1.15rem; }
.ep-file-chip-name { font-size: 0.84rem; font-weight: 600; color: #e2e8f0; word-break: break-all; }
.ep-file-chip-size { font-size: 0.72rem; color: #64748b; }
@keyframes ep-pop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

/* ---- Détection auto ---- */
.ep-detect {
    display: none; flex-wrap: wrap; align-items: center; gap: 0.45rem;
    margin-top: 1rem; padding: 0.8rem 1rem; border-radius: 14px;
    background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(6,182,212,0.08));
    border: 1px solid rgba(16,185,129,0.25);
    animation: ep-pop 0.3s ease;
}
.ep-detect.is-visible { display: flex; }
.ep-detect-label { font-size: 0.78rem; font-weight: 700; color: #34d399; display: inline-flex; align-items: center; gap: 0.4rem; }
.ep-detect-chip {
    font-size: 0.74rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 999px;
    background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.3);
}

/* ---- Aperçu live ---- */
.ep-preview-sticky { position: sticky; top: 1.5rem; }
.ep-preview-card {
    background: #fff; border-radius: 16px; padding: 1.25rem;
    box-shadow: 0 18px 50px rgba(2,6,23,0.5);
    display: flex; flex-direction: column; gap: 0.65rem;
}
.ep-preview-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.ep-preview-badge { font-size: 0.68rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.03em; }
.ep-preview-badge--exam { background: rgba(5,150,105,0.12); color: #047857; }
.ep-preview-badge--type { background: rgba(14,165,233,0.12); color: #0369a1; }
.ep-preview-badge--corrige { background: rgba(245,158,11,0.15); color: #b45309; display: none; }
.ep-preview-title { font-weight: 700; font-size: 0.95rem; line-height: 1.45; color: #0f172a; }
.ep-preview-meta { display: flex; gap: 0.8rem; flex-wrap: wrap; font-size: 0.76rem; color: #64748b; }
.ep-preview-meta i { margin-right: 0.25rem; color: #059669; }
.ep-preview-url {
    margin-top: 0.9rem; font-size: 0.72rem; color: #64748b;
    background: rgba(2,6,23,0.5); border: 1px solid rgba(148,163,184,0.15);
    border-radius: 10px; padding: 0.55rem 0.8rem; word-break: break-all;
}
.ep-preview-url i { color: #34d399; margin-right: 0.35rem; }

/* ---- Submit ---- */
.ep-submit {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem;
    padding: 0.85rem 2rem; border: none; border-radius: 14px; cursor: pointer;
    font-size: 0.95rem; font-weight: 800; color: #022c22;
    background: linear-gradient(135deg, #34d399, #06b6d4);
    box-shadow: 0 8px 24px rgba(16,185,129,0.35);
    transition: transform 0.18s, box-shadow 0.18s;
}
.ep-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(16,185,129,0.45); }
.ep-cancel {
    display: inline-flex; align-items: center; padding: 0.85rem 1.5rem; border-radius: 14px;
    font-size: 0.9rem; font-weight: 600; color: #94a3b8; text-decoration: none;
    border: 1px solid rgba(148,163,184,0.2); transition: all 0.18s;
}
.ep-cancel:hover { color: #e2e8f0; border-color: rgba(148,163,184,0.45); }

/* ---- Toggle vedette ---- */
.ep-toggle { display: inline-flex; align-items: center; gap: 0.65rem; cursor: pointer; user-select: none; }
.ep-toggle input { position: absolute; opacity: 0; }
.ep-toggle-track {
    width: 44px; height: 24px; border-radius: 999px; position: relative; flex-shrink: 0;
    background: rgba(148,163,184,0.25); transition: background 0.2s;
}
.ep-toggle-track::after {
    content: ''; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px;
    border-radius: 50%; background: #e2e8f0; transition: transform 0.2s;
}
.ep-toggle input:checked + .ep-toggle-track { background: linear-gradient(135deg, #34d399, #06b6d4); }
.ep-toggle input:checked + .ep-toggle-track::after { transform: translateX(20px); }
.ep-toggle-label { font-size: 0.88rem; color: #cbd5e1; font-weight: 600; }

/* ---- Light mode ---- */
body.light-mode .ep-card { background: #ffffff; border-color: rgba(15,23,42,0.08); }
body.light-mode .ep-card-title { color: #0f172a; }
body.light-mode .ep-label { color: #64748b; }
body.light-mode .ep-input, body.light-mode .ep-select, body.light-mode .ep-textarea {
    background: #f8fafc; border-color: #e2e8f0; color: #0f172a;
}
body.light-mode .ep-input::placeholder, body.light-mode .ep-textarea::placeholder { color: #94a3b8; }
body.light-mode .ep-pill span { background: #f8fafc; border-color: #e2e8f0; color: #475569; }
body.light-mode .ep-drop { background: #f8fafc; border-color: #cbd5e1; }
body.light-mode .ep-drop-label { color: #1e293b; }
body.light-mode .ep-file-chip-name { color: #1e293b; }
body.light-mode .ep-preview-card { box-shadow: 0 18px 50px rgba(15,23,42,0.15); border: 1px solid #e2e8f0; }
body.light-mode .ep-preview-url { background: #f1f5f9; border-color: #e2e8f0; color: #475569; }
body.light-mode .ep-toggle-label { color: #334155; }
body.light-mode .ep-cancel { color: #64748b; border-color: #cbd5e1; }
body.light-mode .ep-cancel:hover { color: #0f172a; border-color: #94a3b8; }
</style>
@endsection

<div class="ep-form">
    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/40 text-red-300 text-sm">
            <div class="font-bold mb-1.5"><i class="fas fa-triangle-exclamation mr-2"></i>Merci de corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-[1fr_330px] gap-6 items-start">

        {{-- ============ Colonne principale ============ --}}
        <div class="space-y-6 min-w-0">

            {{-- Étape 1 : Fichiers --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <div class="ep-step">1</div>
                    <div>
                        <div class="ep-card-title">Fichiers PDF</div>
                        <div class="ep-card-sub">Glissez-déposez vos fichiers — les métadonnées sont détectées automatiquement</div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <div class="ep-drop" id="dropEpreuve">
                            <input type="file" name="file" id="fileEpreuve" accept="application/pdf" {{ $isEdit ? '' : 'required' }}>
                            <div class="ep-drop-icon"><i class="fas fa-file-arrow-up"></i></div>
                            <div class="ep-drop-label">Épreuve — <em>cliquez ou déposez</em></div>
                            <div class="ep-drop-sub">PDF uniquement · 25 Mo max {{ $isEdit ? '· laissez vide pour conserver' : '' }}</div>
                            <div class="ep-file-chip" id="chipEpreuve">
                                <i class="fas fa-file-pdf"></i>
                                <div>
                                    <div class="ep-file-chip-name" id="chipEpreuveName"></div>
                                    <div class="ep-file-chip-size" id="chipEpreuveSize"></div>
                                </div>
                            </div>
                        </div>
                        @if($isEdit && $epreuve->file_path)
                            <p class="ep-hint"><i class="fas fa-paperclip mr-1"></i>Actuel : {{ $epreuve->file_name ?? basename($epreuve->file_path) }} ({{ $epreuve->file_size_human }})</p>
                        @endif
                        @error('file')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="ep-drop ep-drop--corrige" id="dropCorrige">
                            <input type="file" name="corrige_file" id="fileCorrige" accept="application/pdf">
                            <div class="ep-drop-icon"><i class="fas fa-circle-check"></i></div>
                            <div class="ep-drop-label">Corrigé — <em>optionnel</em></div>
                            <div class="ep-drop-sub">PDF uniquement · 25 Mo max</div>
                            <div class="ep-file-chip" id="chipCorrige">
                                <i class="fas fa-file-pdf"></i>
                                <div>
                                    <div class="ep-file-chip-name" id="chipCorrigeName"></div>
                                    <div class="ep-file-chip-size" id="chipCorrigeSize"></div>
                                </div>
                            </div>
                        </div>
                        @if($isEdit && $epreuve->corrige_file_path)
                            <p class="ep-hint"><i class="fas fa-paperclip mr-1"></i>Corrigé actuel : {{ basename($epreuve->corrige_file_path) }}</p>
                        @endif
                        @error('corrige_file')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="ep-detect" id="detectBar">
                    <span class="ep-detect-label"><i class="fas fa-wand-magic-sparkles"></i> Détecté automatiquement :</span>
                    <span id="detectChips" class="contents"></span>
                </div>
            </div>

            {{-- Étape 2 : Informations --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <div class="ep-step">2</div>
                    <div>
                        <div class="ep-card-title">Informations</div>
                        <div class="ep-card-sub">Titre affiché sur le site et type de document</div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="ep-label" for="fieldTitle">Titre *</label>
                        <input type="text" name="title" id="fieldTitle" value="{{ old('title', $epreuve?->title) }}" required
                               placeholder="ex : Épreuve de Mathématiques BAC 2024 — Série S2" class="ep-input">
                        @error('title')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <span class="ep-label">Type de document *</span>
                        <div class="ep-pills">
                            @foreach(\App\Models\Epreuve::TYPES as $key => $label)
                                <label class="ep-pill">
                                    <input type="radio" name="type" value="{{ $key }}" @checked($oldType === $key) required>
                                    <span><i class="fas {{ $typeIcons[$key] ?? 'fa-file' }}"></i>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('type')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="ep-label" for="fieldSlug">Slug <span class="normal-case font-normal opacity-60">(auto si vide)</span></label>
                        <input type="text" name="slug" id="fieldSlug" value="{{ old('slug', $epreuve?->slug) }}"
                               placeholder="epreuve-maths-bac-s2-2024" class="ep-input">
                        @error('slug')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Étape 3 : Classification --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <div class="ep-step">3</div>
                    <div>
                        <div class="ep-card-title">Classification</div>
                        <div class="ep-card-sub">Examen ou classe, matière, série et session</div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <span class="ep-label">Examen national</span>
                        <div class="ep-pills">
                            <label class="ep-pill">
                                <input type="radio" name="exam" value="" @checked($oldExam === '' || $oldExam === null)>
                                <span><i class="fas fa-ban"></i>Aucun</span>
                            </label>
                            @foreach(\App\Models\Epreuve::EXAMS as $key => $label)
                                <label class="ep-pill">
                                    <input type="radio" name="exam" value="{{ $key }}" @checked($oldExam === $key)>
                                    <span><i class="fas fa-graduation-cap"></i>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="ep-label" for="fieldLevel">Classe / Niveau</label>
                            <div class="ep-select-wrap">
                                <select name="level" id="fieldLevel" class="ep-select">
                                    <option value="">— Aucune —</option>
                                    @foreach(\App\Models\Epreuve::LEVELS as $group => $levels)
                                        <optgroup label="{{ ['primaire' => 'Primaire', 'college' => 'Collège', 'lycee' => 'Lycée'][$group] }}">
                                            @foreach($levels as $key => $label)
                                                <option value="{{ $key }}" @selected(old('level', $epreuve?->level) === $key)>{{ $label }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="ep-label" for="fieldMatiere">Matière</label>
                            <div class="ep-select-wrap">
                                <select name="matiere_id" id="fieldMatiere" class="ep-select">
                                    <option value="">— Choisir —</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}" data-slug="{{ $matiere->slug }}" @selected(old('matiere_id', $epreuve?->matiere_id) == $matiere->id)>{{ $matiere->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="ep-label" for="fieldMatiereNew">Ou nouvelle matière</label>
                            <input type="text" name="matiere_new" id="fieldMatiereNew" value="{{ old('matiere_new') }}"
                                   placeholder="ex : Sciences physiques" class="ep-input">
                        </div>
                        <div>
                            <label class="ep-label" for="fieldSerie">Série</label>
                            <input type="text" name="serie" id="fieldSerie" value="{{ old('serie', $epreuve?->serie) }}"
                                   placeholder="S2, L1, L'A…" class="ep-input">
                        </div>
                        <div>
                            <label class="ep-label" for="fieldYear">Année (session)</label>
                            <input type="number" name="year" id="fieldYear" value="{{ old('year', $epreuve?->year) }}"
                                   min="1980" max="{{ now()->year + 1 }}" placeholder="{{ now()->year }}" class="ep-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Étape 4 : Publication --}}
            <div class="ep-card">
                <div class="ep-card-head">
                    <div class="ep-step">4</div>
                    <div>
                        <div class="ep-card-title">Description &amp; publication</div>
                        <div class="ep-card-sub">Texte SEO et visibilité sur le site</div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="ep-label" for="fieldDescription">Description <span class="normal-case font-normal opacity-60">(optionnelle, utile pour le SEO)</span></label>
                        <textarea name="description" id="fieldDescription" rows="3" class="ep-textarea"
                                  placeholder="ex : Sujet officiel de l'épreuve de mathématiques du BAC 2024, série S2, premier groupe.">{{ old('description', $epreuve?->description) }}</textarea>
                        @error('description')<p class="ep-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
                        <label class="ep-toggle">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $epreuve?->is_featured))>
                            <span class="ep-toggle-track"></span>
                            <span class="ep-toggle-label"><i class="fas fa-star text-yellow-400 mr-1"></i>Mettre en vedette</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <span class="ep-toggle-label">Statut :</span>
                            <div class="ep-pills">
                                <label class="ep-pill">
                                    <input type="radio" name="status" value="published" @checked(old('status', $epreuve?->status ?? 'published') === 'published')>
                                    <span><i class="fas fa-eye"></i>Publié</span>
                                </label>
                                <label class="ep-pill">
                                    <input type="radio" name="status" value="draft" @checked(old('status', $epreuve?->status) === 'draft')>
                                    <span><i class="fas fa-eye-slash"></i>Brouillon</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="ep-submit">
                            <i class="fas {{ $isEdit ? 'fa-floppy-disk' : 'fa-rocket' }}"></i>
                            {{ $isEdit ? 'Enregistrer les modifications' : 'Publier l\'épreuve' }}
                        </button>
                        <a href="{{ route('admin.epreuves.index') }}" class="ep-cancel">Annuler</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ Aperçu live ============ --}}
        <div class="ep-preview-sticky hidden lg:block">
            <div class="ep-card" style="padding: 1.25rem;">
                <div class="ep-card-head" style="margin-bottom: 1rem;">
                    <div class="ep-step" style="background: linear-gradient(135deg, #818cf8, #06b6d4);"><i class="fas fa-eye text-xs"></i></div>
                    <div>
                        <div class="ep-card-title" style="font-size: 0.95rem;">Aperçu en direct</div>
                        <div class="ep-card-sub">La carte telle qu'elle apparaîtra sur le site</div>
                    </div>
                </div>

                <div class="ep-preview-card">
                    <div class="ep-preview-badges">
                        <span class="ep-preview-badge ep-preview-badge--exam" id="pvExam" style="display:none;"></span>
                        <span class="ep-preview-badge ep-preview-badge--type" id="pvType">Épreuve</span>
                        <span class="ep-preview-badge ep-preview-badge--corrige" id="pvCorrige">Corrigé inclus</span>
                    </div>
                    <div class="ep-preview-title" id="pvTitle">Titre de l'épreuve…</div>
                    <div class="ep-preview-meta">
                        <span id="pvMatiere" style="display:none;"><i class="fas fa-book"></i><b class="font-normal"></b></span>
                        <span id="pvSerie" style="display:none;"><i class="fas fa-layer-group"></i><b class="font-normal"></b></span>
                        <span id="pvYear" style="display:none;"><i class="fas fa-calendar"></i><b class="font-normal"></b></span>
                        <span><i class="fas fa-download"></i>{{ $isEdit ? number_format($epreuve->downloads_count, 0, ',', ' ') : '0' }}</span>
                    </div>
                </div>

                <div class="ep-preview-url">
                    <i class="fas fa-link"></i>{{ url('/epreuves') }}/<span id="pvSlug">…</span>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
(function () {
    'use strict';

    var MATIERES = @json($matieres->map(fn ($m) => ['id' => $m->id, 'name' => $m->name, 'slug' => $m->slug])->values());
    var EXAMS = @json(\App\Models\Epreuve::EXAMS);
    var LEVELS = @json(\App\Models\Epreuve::flatLevels());
    var TYPES = @json(\App\Models\Epreuve::TYPES);
    var IS_EDIT = {{ $isEdit ? 'true' : 'false' }};

    function $id(id) { return document.getElementById(id); }
    function normalize(s) {
        return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[_\-.]+/g, ' ');
    }
    function slugify(s) {
        return normalize(s).replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    }
    function humanSize(bytes) {
        if (bytes >= 1048576) return (bytes / 1048576).toFixed(1).replace('.', ',') + ' Mo';
        return Math.round(bytes / 1024) + ' Ko';
    }

    /* ---------- Dropzones ---------- */
    function bindDrop(dropId, inputId, chipId, onFile) {
        var drop = $id(dropId), input = $id(inputId), chip = $id(chipId);
        if (!drop || !input) return;

        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('is-drag'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('is-drag'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            }
        });
        input.addEventListener('change', function () {
            var f = input.files[0];
            if (!f) { chip.classList.remove('is-visible'); return; }
            chip.querySelector('.ep-file-chip-name').textContent = f.name;
            chip.querySelector('.ep-file-chip-size').textContent = humanSize(f.size);
            chip.classList.add('is-visible');
            if (onFile) onFile(f);
        });
    }

    /* ---------- Détection auto des métadonnées ---------- */
    function detectFromName(name) {
        var n = normalize(name);
        var found = {};

        var year = n.match(/\b(19[89]\d|20[0-4]\d)\b/);
        if (year) found.year = year[1];

        Object.keys(EXAMS).forEach(function (key) {
            if (found.exam) return;
            if (new RegExp('\\b' + key + '\\b').test(n) || (key === 'bac' && n.indexOf('baccalaureat') !== -1)) found.exam = key;
        });
        if (!found.exam && (n.indexOf('cfee') !== -1 || /\bcep\b/.test(n))) found.exam = 'cfee';

        Object.keys(LEVELS).forEach(function (key) {
            if (found.level) return;
            if (new RegExp('\\b' + normalize(LEVELS[key]) + '\\b').test(n)) found.level = key;
        });

        if (n.indexOf('blanc') !== -1) found.type = 'examen_blanc';
        else if (n.indexOf('corrige') !== -1 && (n.indexOf('epreuve') !== -1 || n.indexOf('sujet') !== -1)) found.type = 'epreuve_corrige';
        else if (n.indexOf('corrige') !== -1) found.type = 'corrige';
        else if (n.indexOf('composition') !== -1) found.type = 'composition';
        else if (n.indexOf('devoir') !== -1) found.type = 'devoir';

        var serie = n.match(/\bseries?\s+([a-z][a-z0-9]?\d?)\b/);
        if (serie) found.serie = serie[1].toUpperCase();

        for (var i = 0; i < MATIERES.length; i++) {
            var mslug = MATIERES[i].slug.replace(/-/g, ' ');
            if (n.indexOf(normalize(MATIERES[i].name)) !== -1 || n.indexOf(mslug) !== -1) { found.matiere = MATIERES[i]; break; }
        }
        if (!found.matiere) {
            var aliases = [
                [/\bmaths?\b/, 'mathematiques'],
                [/physique|chimie|\bpc\b/, 'physique-chimie'],
                [/histoire|geographie|\bhg\b/, 'histoire-geographie'],
                [/\bsvt\b|sciences de la vie/, 'svt'],
                [/philo/, 'philosophie'],
            ];
            for (var j = 0; j < aliases.length; j++) {
                if (aliases[j][0].test(n)) {
                    found.matiere = MATIERES.find(function (m) { return m.slug === aliases[j][1]; }) || null;
                    if (found.matiere) break;
                }
            }
        }

        return found;
    }

    function applyDetection(found) {
        var chips = [];

        if (found.type) {
            var typeRadio = document.querySelector('input[name="type"][value="' + found.type + '"]');
            var currentType = document.querySelector('input[name="type"]:checked');
            if (typeRadio && (!currentType || currentType.value === 'epreuve')) {
                typeRadio.checked = true;
                chips.push(TYPES[found.type]);
            }
        }
        if (found.exam) {
            var examChecked = document.querySelector('input[name="exam"]:checked');
            if (!examChecked || examChecked.value === '') {
                var examRadio = document.querySelector('input[name="exam"][value="' + found.exam + '"]');
                if (examRadio) { examRadio.checked = true; chips.push(EXAMS[found.exam]); }
            }
        }
        if (found.level && !$id('fieldLevel').value) {
            $id('fieldLevel').value = found.level;
            chips.push(LEVELS[found.level]);
        }
        if (found.matiere && !$id('fieldMatiere').value) {
            $id('fieldMatiere').value = found.matiere.id;
            chips.push(found.matiere.name);
        }
        if (found.serie && !$id('fieldSerie').value) {
            $id('fieldSerie').value = found.serie;
            chips.push('Série ' + found.serie);
        }
        if (found.year && !$id('fieldYear').value) {
            $id('fieldYear').value = found.year;
            chips.push(found.year);
        }

        if (chips.length) {
            var bar = $id('detectBar'), holder = $id('detectChips');
            holder.innerHTML = '';
            chips.forEach(function (c) {
                var el = document.createElement('span');
                el.className = 'ep-detect-chip';
                el.textContent = c;
                holder.appendChild(el);
            });
            bar.classList.add('is-visible');
        }

        refreshPreview();
    }

    /* ---------- Aperçu live ---------- */
    function setPv(id, value, suffix) {
        var el = $id(id);
        if (!el) return;
        if (value) {
            el.style.display = '';
            var b = el.querySelector('b');
            if (b) b.textContent = (suffix || '') + value; else el.textContent = value;
        } else {
            el.style.display = 'none';
        }
    }

    function refreshPreview() {
        var title = $id('fieldTitle').value.trim();
        $id('pvTitle').textContent = title || 'Titre de l’épreuve…';

        var typeChecked = document.querySelector('input[name="type"]:checked');
        $id('pvType').textContent = typeChecked ? TYPES[typeChecked.value] : 'Épreuve';

        var examChecked = document.querySelector('input[name="exam"]:checked');
        var levelVal = $id('fieldLevel').value;
        var examLabel = examChecked && examChecked.value ? EXAMS[examChecked.value] : (levelVal ? LEVELS[levelVal] : '');
        setPv('pvExam', examLabel);

        var matiereSel = $id('fieldMatiere');
        var matiereName = matiereSel.value ? matiereSel.options[matiereSel.selectedIndex].text : $id('fieldMatiereNew').value.trim();
        setPv('pvMatiere', matiereName);
        setPv('pvSerie', $id('fieldSerie').value.trim(), 'Série ');
        setPv('pvYear', $id('fieldYear').value.trim());

        var hasCorrige = ($id('fileCorrige').files.length > 0) || {{ ($isEdit && $epreuve->corrige_file_path) ? 'true' : 'false' }}
            || (typeChecked && (typeChecked.value === 'corrige' || typeChecked.value === 'epreuve_corrige'));
        $id('pvCorrige').style.display = hasCorrige ? '' : 'none';

        var slugField = $id('fieldSlug').value.trim();
        $id('pvSlug').textContent = slugField || slugify(title) || '…';
    }

    /* ---------- Auto-slug (création uniquement) ---------- */
    var slugTouched = IS_EDIT || $id('fieldSlug').value !== '';
    $id('fieldSlug').addEventListener('input', function () { slugTouched = this.value !== ''; });
    $id('fieldTitle').addEventListener('input', function () {
        if (!slugTouched) $id('fieldSlug').placeholder = slugify(this.value) || 'epreuve-maths-bac-s2-2024';
    });

    /* ---------- Bindings ---------- */
    bindDrop('dropEpreuve', 'fileEpreuve', 'chipEpreuve', function (f) {
        applyDetection(detectFromName(f.name));
        if (!$id('fieldTitle').value) {
            var base = f.name.replace(/\.pdf$/i, '').replace(/[_\-.]+/g, ' ').trim();
            $id('fieldTitle').value = base.charAt(0).toUpperCase() + base.slice(1);
            refreshPreview();
        }
    });
    bindDrop('dropCorrige', 'fileCorrige', 'chipCorrige', function () { refreshPreview(); });

    ['fieldTitle', 'fieldSlug', 'fieldSerie', 'fieldYear', 'fieldLevel', 'fieldMatiere', 'fieldMatiereNew'].forEach(function (id) {
        var el = $id(id);
        el.addEventListener('input', refreshPreview);
        el.addEventListener('change', refreshPreview);
    });
    document.querySelectorAll('input[name="type"], input[name="exam"], input[name="status"]').forEach(function (r) {
        r.addEventListener('change', refreshPreview);
    });
    $id('fieldTitle').addEventListener('blur', function () {
        if (this.value) applyDetection(detectFromName(this.value));
    });

    refreshPreview();
})();
</script>
@endsection
