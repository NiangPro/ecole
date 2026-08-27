@extends('layouts.app')

@section('title', 'Panier - Documents - NiangProgrammeur')

@push('styles')
<style>
/* ══════════ PANIER — REFONTE ULTRA MODERNE & SIMPLE ══════════ */
.ncart{
    --nc-bg:#f6f8fb;
    --nc-surface:#ffffff;
    --nc-border:#e8edf3;
    --nc-text:#0f172a;
    --nc-muted:#64748b;
    --nc-faint:#94a3b8;
    --nc-accent:#06b6d4;
    --nc-accent-2:#0891b2;
    --nc-accent-soft:rgba(6,182,212,.08);
    --nc-danger:#ef4444;
    --nc-danger-soft:rgba(239,68,68,.08);
    --nc-shadow:0 1px 3px rgba(15,23,42,.04),0 8px 24px rgba(15,23,42,.05);
    --nc-radius:18px;
    min-height:70vh;
    padding:calc(var(--spacing-navbar, 76px) + 1.5rem) 1.25rem 4rem;
    background:var(--nc-bg);
    color:var(--nc-text);
}
body.dark-mode .ncart{
    --nc-bg:#0b1220;
    --nc-surface:#111a2b;
    --nc-border:#1f2c40;
    --nc-text:#e2e8f0;
    --nc-muted:#94a3b8;
    --nc-faint:#64748b;
    --nc-accent-soft:rgba(6,182,212,.12);
    --nc-shadow:0 1px 3px rgba(0,0,0,.3),0 10px 30px rgba(0,0,0,.35);
}
.ncart *{box-sizing:border-box;}
.ncart-wrap{max-width:1120px;margin:0 auto;}

/* Header */
.ncart-head{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem;}
.ncart-head h1{font-size:1.9rem;font-weight:800;letter-spacing:-.02em;margin:0;display:flex;align-items:center;gap:.6rem;}
.ncart-head h1 i{color:var(--nc-accent);font-size:1.5rem;}
.ncart-head .ncart-sub{margin:.35rem 0 0;color:var(--nc-muted);font-size:.95rem;}
.ncart-back{display:inline-flex;align-items:center;gap:.45rem;color:var(--nc-muted);text-decoration:none;font-weight:600;font-size:.9rem;padding:.55rem .9rem;border:1px solid var(--nc-border);border-radius:10px;background:var(--nc-surface);transition:.18s;}
.ncart-back:hover{color:var(--nc-accent);border-color:var(--nc-accent);}

/* Layout */
.ncart-grid{display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;}
@media (max-width:900px){.ncart-grid{grid-template-columns:1fr;}}

/* Items card */
.ncart-items{background:var(--nc-surface);border:1px solid var(--nc-border);border-radius:var(--nc-radius);box-shadow:var(--nc-shadow);overflow:hidden;}
.ncart-items-head{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.35rem;border-bottom:1px solid var(--nc-border);}
.ncart-items-head span{font-weight:700;font-size:.95rem;}
.ncart-items-head .ncart-count{color:var(--nc-muted);font-weight:600;font-size:.85rem;background:var(--nc-accent-soft);color:var(--nc-accent-2);padding:.25rem .7rem;border-radius:999px;}

.ncart-item{display:flex;gap:1.1rem;padding:1.25rem 1.35rem;border-bottom:1px solid var(--nc-border);transition:background .18s;}
.ncart-item:last-child{border-bottom:none;}
.ncart-item:hover{background:var(--nc-accent-soft);}
.ncart-thumb{flex-shrink:0;width:78px;height:78px;border-radius:14px;overflow:hidden;background:var(--nc-bg);border:1px solid var(--nc-border);display:flex;align-items:center;justify-content:center;}
.ncart-thumb img{width:100%;height:100%;object-fit:cover;}
.ncart-thumb i{font-size:1.7rem;color:var(--nc-faint);}

.ncart-info{flex:1;min-width:0;display:flex;flex-direction:column;}
.ncart-item-title{font-size:1.02rem;font-weight:700;line-height:1.35;margin:0 0 .35rem;}
.ncart-item-title a{color:var(--nc-text);text-decoration:none;}
.ncart-item-title a:hover{color:var(--nc-accent);}
.ncart-tags{display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.75rem;}
.ncart-tag{font-size:.72rem;font-weight:600;padding:.22rem .6rem;border-radius:999px;background:var(--nc-bg);color:var(--nc-muted);border:1px solid var(--nc-border);}
.ncart-tag.pack{background:var(--nc-accent-soft);color:var(--nc-accent-2);border-color:transparent;}

.ncart-item-foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
.ncart-price{font-size:1.15rem;font-weight:800;color:var(--nc-text);transition:transform .2s ease;}
.ncart-item-controls{display:flex;align-items:center;gap:.75rem;}

/* Quantity stepper */
.qty{display:inline-flex;align-items:center;border:1px solid var(--nc-border);border-radius:10px;overflow:hidden;background:var(--nc-surface);}
.qty-btn{width:34px;height:34px;border:none;background:transparent;color:var(--nc-muted);cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center;transition:.15s;}
.qty-btn:hover{background:var(--nc-accent-soft);color:var(--nc-accent);}
.qty-btn:disabled{opacity:.4;cursor:not-allowed;}
.qty-input{width:38px;height:34px;border:none;border-left:1px solid var(--nc-border);border-right:1px solid var(--nc-border);text-align:center;font-weight:700;font-size:.9rem;background:transparent;color:var(--nc-text);-moz-appearance:textfield;}
.qty-input::-webkit-outer-spin-button,.qty-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0;}

.ncart-remove{display:inline-flex;align-items:center;gap:.4rem;background:transparent;border:none;color:var(--nc-faint);cursor:pointer;font-size:.85rem;font-weight:600;padding:.5rem .6rem;border-radius:8px;transition:.15s;}
.ncart-remove:hover{background:var(--nc-danger-soft);color:var(--nc-danger);}

/* Summary */
.ncart-summary{background:var(--nc-surface);border:1px solid var(--nc-border);border-radius:var(--nc-radius);box-shadow:var(--nc-shadow);padding:1.5rem;position:sticky;top:calc(var(--spacing-navbar, 76px) + 1rem);}
.ncart-summary h2{font-size:1.05rem;font-weight:800;margin:0 0 1.25rem;display:flex;align-items:center;gap:.55rem;}
.ncart-summary h2 i{color:var(--nc-accent);}
.ncart-sum-row{display:flex;align-items:center;justify-content:space-between;padding:.6rem 0;font-size:.92rem;color:var(--nc-muted);}
.ncart-sum-row .val{font-weight:600;color:var(--nc-text);transition:transform .2s ease;}
.ncart-sum-divider{height:1px;background:var(--nc-border);margin:.5rem 0;}
.ncart-sum-total{padding:.85rem 0 1.25rem;font-size:1rem;}
.ncart-sum-total .lbl{font-weight:700;color:var(--nc-text);}
.ncart-sum-total .val{font-size:1.5rem;font-weight:800;color:var(--nc-accent-2);}

.ncart-checkout{display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;padding:.95rem;border:none;border-radius:12px;font-size:1rem;font-weight:700;color:#fff;text-decoration:none;cursor:pointer;background:linear-gradient(135deg,var(--nc-accent),var(--nc-accent-2));box-shadow:0 8px 22px rgba(6,182,212,.3);transition:.2s;}
.ncart-checkout:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(6,182,212,.4);color:#fff;}
.ncart-clear{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;margin-top:.7rem;padding:.7rem;border:none;background:transparent;color:var(--nc-faint);font-weight:600;font-size:.88rem;cursor:pointer;border-radius:10px;transition:.15s;}
.ncart-clear:hover{background:var(--nc-danger-soft);color:var(--nc-danger);}
.ncart-secure{display:flex;align-items:center;justify-content:center;gap:.45rem;margin-top:1rem;color:var(--nc-faint);font-size:.8rem;}

/* Empty */
.ncart-empty{background:var(--nc-surface);border:1px solid var(--nc-border);border-radius:var(--nc-radius);box-shadow:var(--nc-shadow);text-align:center;padding:4rem 1.5rem;max-width:520px;margin:0 auto;}
.ncart-empty-icon{width:88px;height:88px;margin:0 auto 1.5rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:var(--nc-accent);background:var(--nc-accent-soft);}
.ncart-empty h2{font-size:1.4rem;font-weight:800;margin:0 0 .5rem;}
.ncart-empty p{color:var(--nc-muted);margin:0 0 1.75rem;}
.ncart-empty-btn{display:inline-flex;align-items:center;gap:.55rem;padding:.85rem 1.6rem;border-radius:12px;font-weight:700;text-decoration:none;color:#fff;background:linear-gradient(135deg,var(--nc-accent),var(--nc-accent-2));box-shadow:0 8px 22px rgba(6,182,212,.3);transition:.2s;}
.ncart-empty-btn:hover{transform:translateY(-2px);color:#fff;}

@media (max-width:520px){
    .ncart-item{flex-wrap:wrap;}
    .ncart-thumb{width:64px;height:64px;}
    .ncart-head h1{font-size:1.55rem;}
}

/* ── Modal de confirmation ── */
.modal-overlay{position:fixed;inset:0;z-index:11000;display:none;align-items:center;justify-content:center;padding:1rem;background:rgba(15,23,42,.6);backdrop-filter:blur(4px);}
.modal-overlay.active{display:flex;animation:ncFade .2s ease;}
@keyframes ncFade{from{opacity:0}to{opacity:1}}
.modal-container{width:100%;max-width:410px;background:var(--nc-surface,#fff);border-radius:18px;box-shadow:0 25px 80px rgba(0,0,0,.35);overflow:hidden;animation:ncPop .25s cubic-bezier(.16,1,.3,1);}
body.dark-mode .modal-container{background:#111a2b;}
@keyframes ncPop{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:none}}
.modal-header{padding:1.75rem 1.5rem .75rem;text-align:center;}
.modal-icon{width:56px;height:56px;margin:0 auto 1rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#f59e0b;background:rgba(245,158,11,.12);}
.modal-title{font-size:1.2rem;font-weight:800;margin:0 0 .4rem;color:var(--nc-text,#0f172a);}
body.dark-mode .modal-title{color:#e2e8f0;}
.modal-message{color:#64748b;font-size:.92rem;margin:0;line-height:1.5;}
.modal-body{padding:.5rem 1.5rem 0;}
.modal-item-name{text-align:center;padding:.85rem 1rem;margin:.75rem 0 0;background:var(--nc-accent-soft,rgba(6,182,212,.08));border-radius:12px;color:var(--nc-text,#0f172a);font-size:.95rem;}
body.dark-mode .modal-item-name{color:#e2e8f0;}
.modal-footer{display:flex;gap:.75rem;padding:1.5rem;}
.modal-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.8rem;border:none;border-radius:12px;font-weight:700;font-size:.92rem;cursor:pointer;transition:.18s;}
.modal-btn-cancel{background:#f1f5f9;color:#475569;}
body.dark-mode .modal-btn-cancel{background:#1f2c40;color:#cbd5e1;}
.modal-btn-cancel:hover{background:#e2e8f0;}
.modal-btn-confirm{background:var(--nc-danger,#ef4444);color:#fff;}
.modal-btn-confirm:hover{background:#dc2626;}

/* ── Modal Paiement Wave direct ── */
.wd-overlay{position:fixed;inset:0;z-index:11000;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(15,23,42,.65);backdrop-filter:blur(4px);animation:ncFade .2s ease;}
.wd-modal{width:100%;max-width:440px;max-height:92vh;overflow:hidden;display:flex;flex-direction:column;background:#fff;border-radius:20px;box-shadow:0 25px 80px rgba(0,0,0,.35);animation:ncPop .25s cubic-bezier(.16,1,.3,1);}
body.dark-mode .wd-modal{background:#111a2b;}
.wd-head{position:relative;display:flex;flex-direction:column;align-items:center;gap:.6rem;padding:1.6rem 1.5rem 1.2rem;border-bottom:1px solid #eef2f7;}
body.dark-mode .wd-head{border-bottom-color:rgba(255,255,255,.08);}
.wd-head-icon{width:54px;height:54px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;background:linear-gradient(135deg,#06b6d4,#0891b2);box-shadow:0 8px 20px rgba(6,182,212,.35);}
.wd-title{margin:0;font-size:1.25rem;font-weight:800;color:#0f172a;}
body.dark-mode .wd-title,body.dark-mode .wd-success-title{color:#e2e8f0;}
.wd-close{position:absolute;top:1rem;right:1rem;width:34px;height:34px;border:none;border-radius:50%;background:#f1f5f9;color:#64748b;cursor:pointer;font-size:.9rem;transition:.2s;}
.wd-close:hover{background:#e2e8f0;color:#0f172a;}
body.dark-mode .wd-close{background:#1f2c40;color:#cbd5e1;}
.wd-body{padding:1.4rem 1.5rem 1.6rem;overflow-y:auto;}
.wd-desc{margin:0 0 1rem;color:#64748b;font-size:.92rem;text-align:center;}
.wd-amount{display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.1rem;margin-bottom:1.2rem;border-radius:14px;background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.2);}
.wd-amount span{color:#475569;font-size:.9rem;}
body.dark-mode .wd-amount span{color:#94a3b8;}
.wd-amount strong{color:#0891b2;font-size:1.25rem;font-weight:800;}
.wd-field{margin-bottom:1rem;}
.wd-field label{display:block;margin-bottom:.4rem;font-size:.85rem;font-weight:600;color:#334155;}
body.dark-mode .wd-field label{color:#cbd5e1;}
.wd-field label span{color:#ef4444;}
.wd-field input{width:100%;padding:.8rem 1rem;border:1.5px solid #e2e8f0;border-radius:12px;font-size:.95rem;color:#0f172a;background:#fff;transition:.2s;box-sizing:border-box;}
.wd-field input:focus{outline:none;border-color:#06b6d4;box-shadow:0 0 0 3px rgba(6,182,212,.12);}
body.dark-mode .wd-field input{background:#0b1220;border-color:#1f2c40;color:#e2e8f0;}
.wd-error{margin:.2rem 0 .8rem;padding:.6rem .8rem;border-radius:10px;background:#fef2f2;color:#dc2626;font-size:.85rem;}
.wd-confirm-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:.95rem;margin-top:.4rem;border:none;border-radius:14px;font-size:1rem;font-weight:700;color:#fff;cursor:pointer;background:linear-gradient(135deg,#06b6d4,#0891b2);box-shadow:0 8px 22px rgba(6,182,212,.35);transition:.2s;}
.wd-confirm-btn:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(6,182,212,.45);}
.wd-confirm-btn:disabled{opacity:.7;cursor:not-allowed;transform:none;}
.wd-success-icon{width:64px;height:64px;margin:.2rem auto 1rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.7rem;color:#f59e0b;background:rgba(245,158,11,.12);}
.wd-success-title{margin:0 0 .6rem;text-align:center;font-size:1.15rem;font-weight:800;color:#0f172a;}
.wd-success-text{margin:0 0 1.2rem;text-align:center;color:#64748b;font-size:.92rem;line-height:1.5;}
.wd-info-box{display:flex;gap:.8rem;padding:1rem;margin-bottom:1.2rem;border-radius:14px;background:rgba(245,158,11,.08);border-left:3px solid #f59e0b;color:#475569;font-size:.9rem;line-height:1.5;}
body.dark-mode .wd-info-box{color:#cbd5e1;}
.wd-info-box>i{color:#f59e0b;font-size:1.1rem;margin-top:.15rem;}
.wd-phone-link{display:inline-flex;align-items:center;gap:.4rem;margin-top:.5rem;padding:.5rem .9rem;border-radius:10px;background:#0f172a;color:#fff !important;font-weight:700;text-decoration:none;font-size:.95rem;}
.wd-phone-link:hover{background:#1e293b;}
.wd-reopen-btn{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:.85rem;margin-bottom:.7rem;border-radius:12px;font-weight:700;text-decoration:none;color:#0891b2;background:rgba(6,182,212,.08);border:1.5px solid rgba(6,182,212,.3);transition:.2s;}
.wd-reopen-btn:hover{background:rgba(6,182,212,.15);}
.wd-done-btn{width:100%;padding:.8rem;border:none;border-radius:12px;background:#f1f5f9;color:#475569;font-weight:600;cursor:pointer;transition:.2s;}
.wd-done-btn:hover{background:#e2e8f0;}
body.dark-mode .wd-done-btn{background:#1f2c40;color:#cbd5e1;}
</style>
@endpush

@section('content')
<div class="ncart">
    <div class="ncart-wrap">
        <div class="ncart-head">
            <div>
                <h1><i class="fas fa-shopping-bag"></i> Mon panier</h1>
                <p class="ncart-sub">Vérifiez vos articles avant de procéder au paiement.</p>
            </div>
            <a href="{{ route('documents.index') }}" class="ncart-back">
                <i class="fas fa-arrow-left"></i> Continuer mes achats
            </a>
        </div>

        @if($cartItems->count() > 0)
        <div class="ncart-grid">
            {{-- Liste des articles --}}
            <div class="ncart-items">
                <div class="ncart-items-head">
                    <span>Articles</span>
                    <span class="ncart-count">{{ $cartItems->count() }} {{ $cartItems->count() > 1 ? 'articles' : 'article' }}</span>
                </div>

                @foreach($cartItems as $item)
                <div class="ncart-item" data-item-id="{{ $item->id }}">
                    <div class="ncart-thumb">
                        @if($item->document->cover_image)
                            @if($item->document->cover_type === 'internal')
                                <img loading="lazy" src="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHours(24), ['id' => $item->document->id]) }}" alt="{{ $item->document->title }}">
                            @else
                                <img loading="lazy" src="{{ $item->document->cover_image }}" alt="{{ $item->document->title }}">
                            @endif
                        @else
                            <i class="fas fa-file-{{ $item->document->file_extension === 'pdf' ? 'pdf' : ($item->document->file_extension === 'doc' || $item->document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                        @endif
                    </div>

                    <div class="ncart-info">
                        <h3 class="ncart-item-title">
                            <a href="{{ route('documents.show', $item->document->slug) }}">{{ $item->document->title }}</a>
                        </h3>
                        <div class="ncart-tags">
                            <span class="ncart-tag">{{ $item->document->category->name }}</span>
                            @if($item->bundle)
                            <span class="ncart-tag pack"><i class="fas fa-box"></i> Pack : {{ $item->bundle->name }}</span>
                            @endif
                        </div>

                        <div class="ncart-item-foot">
                            <div class="ncart-price cart-item-price" data-item-price="{{ $item->price }}">
                                {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                            </div>

                            <div class="ncart-item-controls">
                                @if(!$item->bundle)
                                <form action="{{ route('documents.cart.update', $item->id) }}" method="POST" class="quantity-form" data-item-id="{{ $item->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="qty">
                                        <button type="button" class="qty-btn" onclick="decreaseQuantity({{ $item->id }})" aria-label="Diminuer"><i class="fas fa-minus"></i></button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="qty-input" id="quantity-{{ $item->id }}" readonly>
                                        <button type="button" class="qty-btn" onclick="increaseQuantity({{ $item->id }})" aria-label="Augmenter"><i class="fas fa-plus"></i></button>
                                    </div>
                                </form>
                                @endif

                                <form action="{{ route('documents.cart.remove', $item->id) }}" method="POST" class="remove-item-form" data-item-id="{{ $item->id }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="ncart-remove" onclick="showRemoveConfirmModal({{ $item->id }}, '{{ addslashes($item->document->title) }}')">
                                        <i class="fas fa-trash-alt"></i> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Résumé --}}
            <div class="ncart-summary">
                <h2><i class="fas fa-receipt"></i> Résumé</h2>

                <div class="ncart-sum-row">
                    <span>Sous-total</span>
                    <span class="val" id="subtotal-value">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="ncart-sum-row">
                    <span>Frais de service</span>
                    <span class="val">0 FCFA</span>
                </div>

                <div class="ncart-sum-divider"></div>

                <div class="ncart-sum-row ncart-sum-total">
                    <span class="lbl">Total</span>
                    <span class="val" id="total-value">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <button type="button" class="ncart-checkout" onclick="openWaveDirectModal()">
                    <i class="fas fa-credit-card"></i> Procéder au paiement
                </button>

                <form action="{{ route('documents.cart.clear') }}" method="POST" id="clear-cart-form">
                    @csrf
                    <button type="button" class="ncart-clear" onclick="showClearCartModal()">
                        <i class="fas fa-trash-alt"></i> Vider le panier
                    </button>
                </form>

                <div class="ncart-secure">
                    <i class="fas fa-shield-alt"></i> Paiement 100 % sécurisé
                </div>
            </div>
        </div>
        @else
        <div class="ncart-empty">
            <div class="ncart-empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <h2>Votre panier est vide</h2>
            <p>Ajoutez des documents à votre panier pour commencer.</p>
            <a href="{{ route('documents.index') }}" class="ncart-empty-btn">
                <i class="fas fa-arrow-left"></i> Parcourir les documents
            </a>
        </div>
        @endif
    </div>
</div>

{{-- Modal de confirmation --}}
<div class="modal-overlay" id="confirmModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h2 class="modal-title" id="modalTitle">Confirmation</h2>
            <p class="modal-message" id="modalMessage">Êtes-vous sûr de vouloir continuer ?</p>
        </div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()"><i class="fas fa-times"></i> Annuler</button>
            <button type="button" class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()"><i class="fas fa-check"></i> Confirmer</button>
        </div>
    </div>
</div>

{{-- ══════════ MODAL PAIEMENT WAVE DIRECT (PANIER) ══════════ --}}
@if($cartItems->count() > 0)
<div id="wave-direct-modal" class="wd-overlay" style="display:none;">
    <div class="wd-modal">
        <div class="wd-head">
            <div class="wd-head-icon"><i class="fas fa-wave-square"></i></div>
            <h2 class="wd-title">Paiement Wave</h2>
            <button type="button" class="wd-close" onclick="closeWaveDirectModal()" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="wd-body">
            {{-- ÉTAPE 1 : Formulaire --}}
            <div id="wd-step-form">
                <p class="wd-desc">Renseignez vos informations pour recevoir vos documents.</p>
                <div class="wd-amount">
                    <span>Montant à payer</span>
                    <strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong>
                </div>

                <form id="wd-form" onsubmit="return false;">
                    @csrf
                    <div class="wd-field">
                        <label for="wd-name">Nom complet <span>*</span></label>
                        <input type="text" id="wd-name" name="customer_name" required placeholder="Votre nom complet" autocomplete="name">
                    </div>
                    <div class="wd-field">
                        <label for="wd-email">Email <span>*</span></label>
                        <input type="email" id="wd-email" name="customer_email" required placeholder="votre@email.com" autocomplete="email">
                    </div>
                    <div class="wd-field">
                        <label for="wd-phone">Numéro de téléphone <span>*</span></label>
                        <input type="tel" id="wd-phone" name="customer_phone" required placeholder="+221 77 000 00 00" autocomplete="tel">
                    </div>
                    <p class="wd-error" id="wd-error" style="display:none;"></p>
                    <button type="button" class="wd-confirm-btn" id="wd-confirm-btn" onclick="confirmWaveDirect()">
                        <i class="fas fa-check-circle"></i>
                        <span>Confirmer et payer avec Wave</span>
                    </button>
                </form>
            </div>

            {{-- ÉTAPE 2 : Confirmation / infos --}}
            <div id="wd-step-success" style="display:none;">
                <div class="wd-success-icon"><i class="fas fa-clock"></i></div>
                <h3 class="wd-success-title">Paiement enregistré (en attente)</h3>
                <p class="wd-success-text">
                    Un nouvel onglet Wave s'est ouvert avec le <strong>QR code à payer</strong>.
                    Une fois le paiement effectué, vos documents vous seront envoyés par email.
                </p>
                <div class="wd-info-box">
                    <i class="fas fa-hourglass-half"></i>
                    <div>
                        L'envoi des documents prend généralement <strong>jusqu'à 4 heures</strong>.
                        Si c'est urgent, appelez-nous directement :
                        <a href="tel:{{ $siteSettings->contact_phone ?? '+221783123657' }}" class="wd-phone-link">
                            <i class="fas fa-phone"></i> {{ $siteSettings->contact_phone ?? '+221 78 312 36 57' }}
                        </a>
                    </div>
                </div>
                <a href="#" id="wd-reopen-wave" target="_blank" rel="noopener" class="wd-reopen-btn">
                    <i class="fas fa-mobile-alt"></i> Rouvrir la page de paiement Wave
                </a>
                <button type="button" class="wd-done-btn" onclick="closeWaveDirectModal()">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endif

@section('scripts')
<script>
// ══════════ MODAL PAIEMENT WAVE DIRECT (PANIER) ══════════
window.openWaveDirectModal = function() {
    const modal = document.getElementById('wave-direct-modal');
    if (!modal) return;
    document.getElementById('wd-step-form').style.display = 'block';
    document.getElementById('wd-step-success').style.display = 'none';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

window.closeWaveDirectModal = function() {
    const modal = document.getElementById('wave-direct-modal');
    if (!modal) return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
};

window.confirmWaveDirect = function() {
    const name  = document.getElementById('wd-name').value.trim();
    const email = document.getElementById('wd-email').value.trim();
    const phone = document.getElementById('wd-phone').value.trim();
    const errorEl = document.getElementById('wd-error');
    const btn = document.getElementById('wd-confirm-btn');

    errorEl.style.display = 'none';

    if (!name || !email || !phone) {
        errorEl.textContent = 'Merci de remplir tous les champs.';
        errorEl.style.display = 'block';
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorEl.textContent = 'Merci de saisir une adresse email valide.';
        errorEl.style.display = 'block';
        return;
    }

    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Traitement en cours...</span>';

    // Ouvrir l'onglet immédiatement (évite le blocage popup) puis le rediriger vers Wave
    const waveTab = window.open('', '_blank');

    const fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('payment_method', 'wave');
    fd.append('customer_name', name);
    fd.append('customer_email', email);
    fd.append('customer_phone', phone);

    fetch('{{ route('documents.checkout.process') }}', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json().then(data => ({ ok: r.ok, data })))
    .then(({ ok, data }) => {
        if (ok && data.success && data.wave_link) {
            if (waveTab) {
                waveTab.location.href = data.wave_link;
            } else {
                window.open(data.wave_link, '_blank');
            }
            const reopen = document.getElementById('wd-reopen-wave');
            if (reopen) reopen.href = data.wave_link;
            document.getElementById('wd-step-form').style.display = 'none';
            document.getElementById('wd-step-success').style.display = 'block';
        } else {
            if (waveTab) waveTab.close();
            errorEl.textContent = data.message || 'Une erreur est survenue. Veuillez réessayer.';
            errorEl.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    })
    .catch(() => {
        if (waveTab) waveTab.close();
        errorEl.textContent = 'Une erreur réseau est survenue. Veuillez réessayer.';
        errorEl.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
};

document.addEventListener('DOMContentLoaded', function() {
    const wdOverlay = document.getElementById('wave-direct-modal');
    if (wdOverlay) {
        wdOverlay.addEventListener('click', function(e) {
            if (e.target === this) closeWaveDirectModal();
        });
    }
});

// S'assurer que les fonctions sont dans le scope global et disponibles immédiatement
window.increaseQuantity = function(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    if (!input) {
        console.error('Input not found for item:', itemId);
        return;
    }
    const currentValue = parseInt(input.value) || 1;
    if (currentValue < 10) {
        const newValue = currentValue + 1;
        input.value = newValue;
        window.updateQuantity(itemId, newValue);
    }
};

window.decreaseQuantity = function(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    if (!input) {
        console.error('Input not found for item:', itemId);
        return;
    }
    const currentValue = parseInt(input.value) || 1;
    if (currentValue > 1) {
        const newValue = currentValue - 1;
        input.value = newValue;
        window.updateQuantity(itemId, newValue);
    }
};

window.updateQuantity = function(itemId, quantity) {
    const form = document.querySelector(`form[data-item-id="${itemId}"]`);
    if (!form) {
        console.error('Form not found for item:', itemId);
        return;
    }

    const formData = new FormData(form);
    formData.set('quantity', quantity);

    // Désactiver les boutons pendant la requête
    const buttons = form.querySelectorAll('.qty-btn');
    buttons.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.5';
    });

    // Afficher un indicateur de chargement
    const input = document.getElementById('quantity-' + itemId);
    input.style.opacity = '0.5';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('HTTP error! status: ' + response.status + ', body: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Mettre à jour le sous-total de l'item
            const itemRow = form.closest('.ncart-item');
            const priceElement = itemRow.querySelector('.cart-item-price');
            if (priceElement && data.item_subtotal) {
                priceElement.textContent = data.item_subtotal;
                // Animation de mise à jour
                priceElement.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    priceElement.style.transform = 'scale(1)';
                }, 200);
            }

            // Mettre à jour le total dans le résumé
            const totalElement = document.getElementById('total-value');
            const subtotalElement = document.getElementById('subtotal-value');
            if (totalElement && data.total) {
                totalElement.textContent = data.total;
                totalElement.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    totalElement.style.transform = 'scale(1)';
                }, 200);
            }
            if (subtotalElement && data.total) {
                subtotalElement.textContent = data.total;
            }
        } else {
            throw new Error(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de la mise à jour. Veuillez réessayer.');
        // Recharger la page en cas d'erreur
        location.reload();
    })
    .finally(() => {
        // Réactiver les boutons
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
        });
        input.style.opacity = '1';
    });
}

// Variables globales pour le modal
let currentForm = null;
let currentAction = null;

// Fonction pour afficher le modal de confirmation de retrait
window.showRemoveConfirmModal = function(itemId, itemTitle) {
    // Trouver le formulaire par data-item-id
    currentForm = document.querySelector(`form.remove-item-form[data-item-id="${itemId}"]`);

    if (!currentForm) {
        console.error('Form not found for item:', itemId);
        return;
    }

    document.getElementById('modalTitle').textContent = 'Retirer du panier';
    document.getElementById('modalMessage').textContent = 'Êtes-vous sûr de vouloir retirer ce document de votre panier ?';

    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="modal-item-name">
            <strong>${itemTitle}</strong>
        </div>
    `;

    const modal = document.getElementById('confirmModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// Fonction pour afficher le modal de confirmation de vider le panier
window.showClearCartModal = function() {
    currentForm = document.getElementById('clear-cart-form');

    document.getElementById('modalTitle').textContent = 'Vider le panier';
    document.getElementById('modalMessage').textContent = 'Êtes-vous sûr de vouloir vider complètement votre panier ?';

    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <p style="text-align: center; color: #ef4444; font-weight: 600; margin-top: 1rem;">
            Cette action est irréversible et supprimera tous les articles de votre panier.
        </p>
    `;

    const modal = document.getElementById('confirmModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
};

// Fonction pour confirmer l'action
window.confirmAction = function() {
    if (currentForm) {
        currentForm.submit();
    }
    closeModal();
};

// Fonction pour fermer le modal
window.closeModal = function() {
    const modal = document.getElementById('confirmModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    currentForm = null;
    currentAction = null;

    // Réinitialiser le contenu du modal
    setTimeout(() => {
        document.getElementById('modalBody').innerHTML = '';
    }, 300);
};

// Fermer le modal en cliquant sur l'overlay
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    }
});
</script>
@endsection
@endsection
