@extends('layouts.app')

@section('title', 'Panier - Documents - NiangProgrammeur')

@push('styles')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.cart-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    position: relative;
    overflow-x: hidden;
}

.cart-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Hero Header Ultra Moderne */
.cart-hero {
    text-align: center;
    padding: 3rem 1rem;
    margin-bottom: 3rem;
    position: relative;
}

.cart-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border-radius: 24px;
    z-index: 0;
}

.cart-hero h1 {
    font-size: 3rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
    text-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
}

.cart-hero p {
    font-size: 1.25rem;
    color: #64748b;
    position: relative;
    z-index: 1;
}

/* Cart Content Grid */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2.5rem;
    align-items: start;
}

/* Cart Items Card - Ultra Moderne */
.cart-items-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    position: relative;
    overflow: hidden;
}

.cart-items-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.cart-items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid rgba(6, 182, 212, 0.1);
}

.cart-items-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.cart-items-title i {
    color: #06b6d4;
    font-size: 1.5rem;
}

.cart-items-count {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.875rem;
}

/* Cart Item - Design Moderne */
.cart-item {
    display: flex;
    gap: 1.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background: rgba(248, 250, 252, 0.8);
    border-radius: 16px;
    border: 1px solid rgba(6, 182, 212, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.cart-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #06b6d4, #14b8a6);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
}

.cart-item:hover::before {
    transform: scaleY(1);
}

.cart-item-image-wrapper {
    width: 140px;
    height: 140px;
    flex-shrink: 0;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
    position: relative;
}

.cart-item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cart-item:hover .cart-item-image {
    transform: scale(1.1);
}

.cart-item-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.cart-item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.cart-item-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    line-height: 1.4;
}

.cart-item-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.cart-item-title a:hover {
    color: #06b6d4;
}

.cart-item-category {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    background: rgba(6, 182, 212, 0.1);
    color: #06b6d4;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    width: fit-content;
}

.cart-item-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(6, 182, 212, 0.1);
}

.cart-item-price {
    font-size: 1.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cart-item-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Quantity Control - Ultra Moderne */
.quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border: 2px solid rgba(6, 182, 212, 0.2);
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.1);
}

.quantity-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    color: white;
    font-size: 1.125rem;
    font-weight: 700;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
}

.quantity-btn:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.5);
}

.quantity-btn:active:not(:disabled) {
    transform: scale(0.95);
}

.quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity-input {
    width: 60px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 700;
    color: #1e293b;
    font-size: 1rem;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.remove-btn {
    padding: 0.75rem 1.25rem;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.remove-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* Cart Summary - Ultra Moderne */
.cart-summary-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    position: sticky;
    top: 100px;
    height: fit-content;
}

.summary-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid rgba(6, 182, 212, 0.1);
}

.summary-title i {
    color: #06b6d4;
    font-size: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.summary-label {
    font-size: 1rem;
    color: #64748b;
    font-weight: 600;
}

.summary-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.summary-total {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 3px solid rgba(6, 182, 212, 0.2);
}

.summary-total .summary-label {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e293b;
}

.summary-total .summary-value {
    font-size: 2rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.checkout-btn {
    width: 100%;
    padding: 1.25rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 800;
    font-size: 1.125rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    text-decoration: none;
}

.checkout-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.5);
}

.clear-cart-btn {
    width: 100%;
    padding: 1rem;
    background: transparent;
    color: #ef4444;
    border: 2px solid #ef4444;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.clear-cart-btn:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 6rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    border: 1px solid rgba(6, 182, 212, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.empty-cart i {
    font-size: 5rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 2rem;
    display: block;
}

.empty-cart h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: #64748b;
    font-size: 1.125rem;
    margin-bottom: 2.5rem;
}

.empty-cart .btn {
    display: inline-flex !important;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem !important;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500 !important;
    font-size: 0.8125rem !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.2);
    max-width: fit-content;
}

.empty-cart .btn i {
    font-size: 0.75rem !important;
}

.empty-cart .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 12px rgba(6, 182, 212, 0.3);
}

@media (max-width: 1024px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .cart-summary-card {
        position: static;
    }
}

@media (max-width: 640px) {
    .cart-hero h1 {
        font-size: 2rem;
    }
    
    .cart-item {
        flex-direction: column;
    }
    
    .cart-item-image-wrapper {
        width: 100%;
        height: 200px;
    }
    
    .cart-item-footer {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .cart-item-actions {
        justify-content: space-between;
    }
}

/* Dark Mode */
body.dark-mode .cart-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

body.dark-mode .cart-hero h1 {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 0 40px rgba(6, 182, 212, 0.5);
}

body.dark-mode .cart-hero p {
    color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .cart-items-card,
body.dark-mode .cart-summary-card,
body.dark-mode .empty-cart {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(6, 182, 212, 0.2);
}

body.dark-mode .cart-item {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .cart-item:hover {
    border-color: rgba(6, 182, 212, 0.4);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.2);
}

body.dark-mode .cart-items-title,
body.dark-mode .summary-title,
body.dark-mode .cart-item-title,
body.dark-mode .cart-item-title a,
body.dark-mode .empty-cart h2 {
    color: white;
}

body.dark-mode .cart-item-title a:hover {
    color: #06b6d4;
}

body.dark-mode .cart-item-category {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
}

body.dark-mode .quantity-control {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .quantity-input {
    color: white;
}

body.dark-mode .summary-label {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .summary-value {
    color: white;
}

body.dark-mode .summary-total .summary-label {
    color: white;
}

/* Modal Ultra Moderne */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 1rem;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border-radius: 24px;
    padding: 0;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.3);
    transform: scale(0.9) translateY(20px);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
}

.modal-header {
    padding: 2rem 2rem 1rem;
    text-align: center;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(239, 68, 68, 0.3);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
}

.modal-icon i {
    font-size: 2.5rem;
    color: #ef4444;
}

.modal-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.75rem;
}

.modal-message {
    font-size: 1.125rem;
    color: #64748b;
    line-height: 1.6;
}

.modal-body {
    padding: 1.5rem 2rem;
}

.modal-item-name {
    background: rgba(6, 182, 212, 0.1);
    padding: 1rem;
    border-radius: 12px;
    margin-top: 1rem;
    border-left: 4px solid #06b6d4;
}

.modal-item-name strong {
    color: #06b6d4;
    font-weight: 700;
}

.modal-footer {
    padding: 1.5rem 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.modal-btn {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 140px;
    justify-content: center;
}

.modal-btn-cancel {
    background: rgba(100, 116, 139, 0.1);
    color: #64748b;
    border: 2px solid rgba(100, 116, 139, 0.3);
}

.modal-btn-cancel:hover {
    background: rgba(100, 116, 139, 0.2);
    border-color: rgba(100, 116, 139, 0.5);
    transform: translateY(-2px);
}

.modal-btn-confirm {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
}

.modal-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(239, 68, 68, 0.5);
}

/* Dark Mode pour Modal */
body.dark-mode .modal-overlay {
    background: rgba(0, 0, 0, 0.85);
}

body.dark-mode .modal-container {
    background: rgba(30, 41, 59, 0.95);
    border-color: rgba(6, 182, 212, 0.4);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(6, 182, 212, 0.3);
}

body.dark-mode .modal-header {
    border-bottom-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .modal-title {
    color: white;
}

body.dark-mode .modal-message {
    color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .modal-item-name {
    background: rgba(6, 182, 212, 0.15);
    border-left-color: #06b6d4;
}

body.dark-mode .modal-item-name strong {
    color: #06b6d4;
}

body.dark-mode .modal-btn-cancel {
    background: rgba(100, 116, 139, 0.2);
    color: rgba(255, 255, 255, 0.9);
    border-color: rgba(100, 116, 139, 0.4);
}

body.dark-mode .modal-btn-cancel:hover {
    background: rgba(100, 116, 139, 0.3);
    border-color: rgba(100, 116, 139, 0.6);
}
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="cart-container">
        <div class="cart-hero">
            <h1>🛒 Mon Panier</h1>
            <p>Vérifiez vos articles avant de procéder au paiement</p>
        </div>

        @if($cartItems->count() > 0)
        <div class="cart-content">
            <div class="cart-items-card">
                <div class="cart-items-header">
                    <h2 class="cart-items-title">
                        <i class="fas fa-shopping-bag"></i> Articles
                    </h2>
                    <span class="cart-items-count">{{ $cartItems->count() }} {{ $cartItems->count() > 1 ? 'articles' : 'article' }}</span>
                </div>

                @foreach($cartItems as $item)
                <div class="cart-item" data-item-id="{{ $item->id }}">
                    <div class="cart-item-image-wrapper">
                        @if($item->document->cover_image)
                            @if($item->document->cover_type === 'internal')
                                <img src="{{ asset('storage/' . $item->document->cover_image) }}" alt="{{ $item->document->title }}" class="cart-item-image">
                            @else
                                <img src="{{ $item->document->cover_image }}" alt="{{ $item->document->title }}" class="cart-item-image">
                            @endif
                        @else
                            <div class="cart-item-image-placeholder">
                                <i class="fas fa-file-{{ $item->document->file_extension === 'pdf' ? 'pdf' : ($item->document->file_extension === 'doc' || $item->document->file_extension === 'docx' ? 'word' : 'alt') }}"></i>
                            </div>
                        @endif
                    </div>

                    <div class="cart-item-details">
                        <h3 class="cart-item-title">
                            <a href="{{ route('documents.show', $item->document->slug) }}">
                                {{ $item->document->title }}
                            </a>
                        </h3>
                        <span class="cart-item-category">{{ $item->document->category->name }}</span>

                        <div class="cart-item-footer">
                            <div class="cart-item-price" data-item-price="{{ $item->price }}">
                                {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                            </div>

                            <div class="cart-item-actions">
                                <form action="{{ route('documents.cart.update', $item->id) }}" method="POST" class="quantity-form" data-item-id="{{ $item->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="quantity-control">
                                        <button type="button" class="quantity-btn" onclick="decreaseQuantity({{ $item->id }})" aria-label="Diminuer">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="quantity-input" id="quantity-{{ $item->id }}" readonly>
                                        <button type="button" class="quantity-btn" onclick="increaseQuantity({{ $item->id }})" aria-label="Augmenter">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('documents.cart.remove', $item->id) }}" method="POST" style="display: inline;" class="remove-item-form" data-item-id="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="remove-btn" onclick="showRemoveConfirmModal({{ $item->id }}, '{{ addslashes($item->document->title) }}')">
                                        <i class="fas fa-trash"></i> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="cart-summary-card">
                <h2 class="summary-title">
                    <i class="fas fa-receipt"></i> Résumé
                </h2>
                
                <div class="summary-row">
                    <span class="summary-label">Sous-total</span>
                    <span class="summary-value" id="subtotal-value">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Frais de service</span>
                    <span class="summary-value">0 FCFA</span>
                </div>

                <div class="summary-row summary-total">
                    <span class="summary-label">Total</span>
                    <span class="summary-value" id="total-value">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <a href="{{ route('documents.checkout.payment') }}" class="checkout-btn">
                    <i class="fas fa-credit-card"></i> Procéder au paiement
                </a>

                <form action="{{ route('documents.cart.clear') }}" method="POST" id="clear-cart-form">
                    @csrf
                    <button type="button" class="clear-cart-btn" onclick="showClearCartModal()">
                        <i class="fas fa-trash-alt"></i> Vider le panier
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>Votre panier est vide</h2>
            <p>Ajoutez des documents à votre panier pour commencer</p>
            <a href="{{ route('documents.index') }}" class="btn">
                <i class="fas fa-arrow-left"></i> Parcourir les documents
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Confirmation Ultra Moderne -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="modal-title" id="modalTitle">Confirmation</h2>
            <p class="modal-message" id="modalMessage">Êtes-vous sûr de vouloir continuer ?</p>
        </div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="button" class="modal-btn modal-btn-confirm" id="modalConfirmBtn" onclick="confirmAction()">
                <i class="fas fa-check"></i> Confirmer
            </button>
        </div>
    </div>
</div>

@section('scripts')
<script>
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
    const buttons = form.querySelectorAll('.quantity-btn');
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
            const itemRow = form.closest('.cart-item');
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

// Ajouter des transitions CSS pour les animations
const style = document.createElement('style');
style.textContent = `
    .cart-item-price,
    #total-value,
    #subtotal-value {
        transition: transform 0.2s ease;
    }
`;
document.head.appendChild(style);

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
