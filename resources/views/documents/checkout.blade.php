@extends('layouts.app')

@section('title', 'Paiement - Documents - NiangProgrammeur')

@push('styles')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.checkout-page {
    min-height: 100vh;
    padding: 2rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
    position: relative;
    overflow-x: hidden;
}

.checkout-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* Hero Header Ultra Moderne */
.checkout-hero {
    text-align: center;
    padding: 3rem 1rem;
    margin-bottom: 3rem;
    position: relative;
}

.checkout-hero::before {
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

.checkout-hero h1 {
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

.checkout-hero p {
    font-size: 1.25rem;
    color: #64748b;
    position: relative;
    z-index: 1;
}

/* Checkout Content Grid */
.checkout-content {
    display: grid;
    grid-template-columns: 1fr 450px;
    gap: 2.5rem;
    align-items: start;
}

/* Checkout Form Card - Ultra Moderne */
.checkout-form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
    position: relative;
    overflow: hidden;
}

.checkout-form-card::before {
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

.form-section-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding-bottom: 0.875rem;
    border-bottom: 2px solid rgba(6, 182, 212, 0.1);
}

.form-section-title i {
    color: #06b6d4;
    font-size: 1.125rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 2px solid rgba(6, 182, 212, 0.2);
    border-radius: 10px;
    font-size: 0.875rem;
    color: #1e293b;
    background: rgba(248, 250, 252, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: #06b6d4;
    background: white;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    transform: translateY(-1px);
}

.form-input::placeholder {
    color: #94a3b8;
}

/* Phone Input Wrapper */
.phone-input-wrapper {
    display: flex;
    gap: 0.75rem;
    align-items: stretch;
}

.country-code-select {
    flex-shrink: 0;
    width: 120px;
    padding: 0.625rem 0.875rem;
    padding-left: 0.625rem;
    border: 2px solid rgba(6, 182, 212, 0.2);
    border-radius: 10px;
    font-size: 0.875rem;
    color: #1e293b;
    background: rgba(248, 250, 252, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: inherit;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.625rem center;
    padding-right: 2rem;
    line-height: 1.5;
}

.country-code-select:focus {
    outline: none;
    border-color: #06b6d4;
    background-color: white;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
}

.phone-input {
    flex: 1;
}

.form-help-text {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-help-text i {
    color: #06b6d4;
}

/* Payment Methods - Ultra Moderne */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.payment-method {
    position: relative;
}

.payment-method input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.payment-method-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border: 2px solid rgba(6, 182, 212, 0.2);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(248, 250, 252, 0.8);
    position: relative;
    overflow: hidden;
}

.payment-method-label::before {
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

.payment-method input[type="radio"]:checked + .payment-method-label {
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.2);
    transform: translateY(-2px);
}

.payment-method input[type="radio"]:checked + .payment-method-label::before {
    transform: scaleY(1);
}

.payment-method-label:hover {
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.05);
    transform: translateY(-1px);
}

.payment-method-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
    border-radius: 8px;
    font-size: 1.125rem;
    color: #06b6d4;
    flex-shrink: 0;
    border: 2px solid rgba(6, 182, 212, 0.2);
    transition: all 0.3s ease;
}

.payment-method input[type="radio"]:checked + .payment-method-label .payment-method-icon {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    transform: scale(1.05);
}

.payment-method-info {
    flex: 1;
}

.payment-method-name {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.payment-method-desc {
    font-size: 0.75rem;
    color: #64748b;
    line-height: 1.4;
}

/* Checkout Summary Card - Ultra Moderne */
.checkout-summary-card {
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

.summary-items {
    margin-bottom: 1.5rem;
    max-height: 300px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.summary-items::-webkit-scrollbar {
    width: 6px;
}

.summary-items::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.1);
    border-radius: 10px;
}

.summary-items::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 10px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item-name {
    font-size: 0.9375rem;
    color: #64748b;
    flex: 1;
    line-height: 1.4;
    padding-right: 1rem;
}

.summary-item-price {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    white-space: nowrap;
}

.summary-total {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 3px solid rgba(6, 182, 212, 0.2);
}

.summary-total .summary-item-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e293b;
}

.summary-total .summary-item-price {
    font-size: 2rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.submit-btn {
    width: 100%;
    padding: 0.875rem 1rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
    position: relative;
    overflow: hidden;
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.submit-btn:hover::before {
    left: 100%;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.5);
}

.submit-btn:active {
    transform: translateY(-1px);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.back-to-cart {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.back-to-cart:hover {
    color: #06b6d4;
    transform: translateX(-4px);
}

.back-to-cart i {
    transition: transform 0.3s ease;
}

.back-to-cart:hover i {
    transform: translateX(-4px);
}

@media (max-width: 1024px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
    
    .checkout-summary-card {
        position: static;
    }
}

@media (max-width: 640px) {
    .checkout-hero h1 {
        font-size: 2rem;
    }
    
    .checkout-form-card,
    .checkout-summary-card {
        padding: 1.5rem;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .payment-method-label {
        padding: 0.75rem;
    }
    
    .payment-method-icon {
        width: 36px;
        height: 36px;
        font-size: 1.125rem;
    }
}

/* Dark Mode */
body.dark-mode .checkout-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
}

body.dark-mode .checkout-hero h1 {
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 0 40px rgba(6, 182, 212, 0.5);
}

body.dark-mode .checkout-hero p {
    color: rgba(255, 255, 255, 0.8);
}

body.dark-mode .checkout-form-card,
body.dark-mode .checkout-summary-card {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(6, 182, 212, 0.2);
}

body.dark-mode .form-section-title,
body.dark-mode .summary-title,
body.dark-mode .form-group label,
body.dark-mode .payment-method-name {
    color: white;
}

body.dark-mode .form-input {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
    color: white;
}

body.dark-mode .form-input:focus {
    background: rgba(15, 23, 42, 0.8);
    border-color: #06b6d4;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2);
}

body.dark-mode .form-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

body.dark-mode .form-help-text {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .payment-method-label {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .payment-method-label:hover {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(6, 182, 212, 0.5);
}

body.dark-mode .payment-method input[type="radio"]:checked + .payment-method-label {
    background: rgba(6, 182, 212, 0.2);
    border-color: #06b6d4;
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.3);
}

body.dark-mode .payment-method-icon {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .payment-method-desc {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .summary-item-name {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .summary-item-price {
    color: white;
}

body.dark-mode .summary-total .summary-item-name {
    color: white;
}

body.dark-mode .back-to-cart {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .back-to-cart:hover {
    color: #06b6d4;
}

body.dark-mode .country-code-select {
    background: rgba(15, 23, 42, 0.6);
    border-color: rgba(6, 182, 212, 0.3);
    color: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2306b6d4' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
}

body.dark-mode .country-code-select:focus {
    background-color: rgba(15, 23, 42, 0.8);
    border-color: #06b6d4;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2);
}

body.dark-mode .country-code-select option {
    background: #1e293b;
    color: white;
}

/* Modal Wave Ultra Moderne */
.wave-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 1rem;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.wave-modal-container {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    max-width: 550px;
    width: 100%;
    max-height: 90vh;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.3);
    overflow: hidden;
    animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.wave-modal-container::before {
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

.wave-modal-header {
    padding: 2rem 2rem 1.5rem;
    text-align: center;
    position: relative;
    flex-shrink: 0;
    background: linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.95) 100%);
    border-bottom: 1px solid rgba(6, 182, 212, 0.1);
}

.wave-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    border: none;
    background: rgba(6, 182, 212, 0.1);
    border-radius: 50%;
    color: #06b6d4;
    font-size: 1.125rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.wave-modal-close:hover {
    background: rgba(6, 182, 212, 0.2);
    transform: rotate(90deg);
}

.wave-modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.wave-modal-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.wave-modal-body {
    padding: 0 2rem 2rem;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
    scroll-behavior: smooth;
}

/* Scrollbar moderne pour le modal */
.wave-modal-body::-webkit-scrollbar {
    width: 8px;
}

.wave-modal-body::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.05);
    border-radius: 10px;
    margin: 1rem 0;
}

.wave-modal-body::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    border-radius: 10px;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.wave-modal-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0891b2, #0d9488);
}

/* Pour Firefox */
.wave-modal-body {
    scrollbar-width: thin;
    scrollbar-color: #06b6d4 rgba(6, 182, 212, 0.05);
}

.wave-modal-description {
    text-align: center;
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.wave-modal-amount {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08), rgba(20, 184, 166, 0.08));
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    text-align: center;
    border: 2px solid rgba(6, 182, 212, 0.2);
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.5);
    position: relative;
    overflow: hidden;
}

.wave-modal-amount::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.wave-amount-label {
    display: block;
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.wave-amount-value {
    display: block;
    font-size: 2rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.wave-qr-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 1.5rem 0;
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(240, 253, 250, 1) 100%);
    border-radius: 20px;
    border: 2px solid rgba(6, 182, 212, 0.2);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.8);
    position: relative;
    overflow: hidden;
}

.wave-qr-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.wave-qr-code {
    max-width: 250px;
    width: 100%;
    height: auto;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
    background: white;
    padding: 0.5rem;
}

.wave-qr-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: #64748b;
}

.wave-qr-placeholder i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #cbd5e1;
}

.wave-qr-placeholder p {
    font-size: 0.875rem;
    margin: 0;
}

.wave-pay-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1.25rem 2rem;
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    border-radius: 16px;
    font-weight: 800;
    font-size: 1.125rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
    border: none;
    cursor: pointer;
}

.wave-confirm-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1.25rem 2rem;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    border-radius: 16px;
    font-weight: 800;
    font-size: 1.125rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
}

.wave-confirm-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.wave-confirm-button:hover::before {
    left: 100%;
}

.wave-confirm-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(34, 197, 94, 0.5);
}

.wave-confirm-button:active {
    transform: translateY(-1px);
}

.wave-confirm-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.wave-pay-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
}

.wave-pay-button:hover::before {
    left: 100%;
}

.wave-pay-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(6, 182, 212, 0.5);
}

.wave-pay-button:active {
    transform: translateY(-1px);
}

.wave-pay-button i:first-child {
    font-size: 1.5rem;
}

.wave-pay-button i:last-child {
    transition: transform 0.3s ease;
}

.wave-pay-button:hover i:last-child {
    transform: translateX(4px);
}

.wave-modal-note {
    text-align: center;
    font-size: 0.875rem;
    color: #64748b;
    margin: 1rem 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
    border-left: 3px solid #06b6d4;
}

.wave-modal-note i {
    color: #06b6d4;
}

/* Dark Mode */
body.dark-mode .wave-modal-container {
    background: rgba(30, 41, 59, 0.98);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(6, 182, 212, 0.2);
}

body.dark-mode .wave-modal-header {
    background: linear-gradient(180deg, rgba(30, 41, 59, 1) 0%, rgba(30, 41, 59, 0.95) 100%);
    border-bottom-color: rgba(6, 182, 212, 0.2);
}

body.dark-mode .wave-modal-body::-webkit-scrollbar-track {
    background: rgba(6, 182, 212, 0.1);
}

body.dark-mode .wave-modal-body {
    scrollbar-color: #06b6d4 rgba(6, 182, 212, 0.1);
}

body.dark-mode .wave-modal-title {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

body.dark-mode .wave-modal-description,
body.dark-mode .wave-amount-label,
body.dark-mode .wave-modal-note {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .wave-modal-amount {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
}

body.dark-mode .wave-modal-close {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
}

body.dark-mode .wave-modal-close:hover {
    background: rgba(6, 182, 212, 0.3);
}

body.dark-mode .wave-qr-container {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.9) 100%);
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 8px 24px rgba(6, 182, 212, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

body.dark-mode .wave-qr-code {
    background: rgba(15, 23, 42, 0.8);
}

body.dark-mode .wave-modal-amount {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.12), rgba(20, 184, 166, 0.12));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 16px rgba(6, 182, 212, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

body.dark-mode .wave-modal-note {
    background: rgba(6, 182, 212, 0.1);
    border-left-color: #06b6d4;
}

body.dark-mode .wave-qr-placeholder {
    color: rgba(255, 255, 255, 0.7);
}

body.dark-mode .wave-qr-placeholder i {
    color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 640px) {
    .wave-modal-container {
        margin: 1rem;
        border-radius: 20px;
    }
    
    .wave-modal-header {
        padding: 1.5rem 1.5rem 1rem;
    }
    
    .wave-modal-body {
        padding: 0 1.5rem 1.5rem;
    }
    
    .wave-modal-icon {
        width: 64px;
        height: 64px;
        font-size: 2rem;
    }
    
    .wave-modal-title {
        font-size: 1.5rem;
    }
    
    .wave-amount-value {
        font-size: 1.5rem;
    }
    
    .wave-pay-button {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
    
    .wave-confirm-button {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
    
    .wave-qr-code {
        max-width: 200px;
    }
    
    .wave-modal-container {
        max-height: 95vh;
    }
    
    .wave-modal-body {
        padding: 0 1.5rem 1.5rem;
    }
    
    .wave-modal-header {
        padding: 1.5rem 1.5rem 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="checkout-container">
        <div class="checkout-hero">
            <h1>💳 Paiement</h1>
            <p>Choisissez votre méthode de paiement sécurisée</p>
        </div>

        <form action="{{ route('documents.checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="checkout-content">
                <div class="checkout-form-card">
                    <div class="form-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-user-circle"></i> Informations de contact
                        </h3>
                        <div style="display: grid; gap: 1.25rem;">
                            @guest
                            <div>
                                <label for="customer_name">Nom complet *</label>
                                <input type="text" name="customer_name" id="customer_name" required 
                                    class="form-input" 
                                    placeholder="Votre nom complet"
                                    value="{{ old('customer_name') }}">
                                @error('customer_name')
                                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div>
                                <label for="customer_email">Adresse email *</label>
                                <input type="email" name="customer_email" id="customer_email" required 
                                    class="form-input" 
                                    placeholder="votre@email.com"
                                    value="{{ old('customer_email') }}">
                                @error('customer_email')
                                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p class="form-help-text">
                                    <i class="fas fa-info-circle"></i>
                                    Vous recevrez le lien de téléchargement à cette adresse
                                </p>
                            </div>
                            @endguest
                            <div>
                                <label for="customer_phone">Numéro de téléphone (optionnel)</label>
                                <div class="phone-input-wrapper">
                                    <select name="country_code" id="country_code" class="country-code-select">
                                        <option value="+221" data-flag="🇸🇳" {{ old('country_code', '+221') === '+221' ? 'selected' : '' }}>🇸🇳 +221</option>
                                        <option value="+33" data-flag="🇫🇷" {{ old('country_code') === '+33' ? 'selected' : '' }}>🇫🇷 +33</option>
                                        <option value="+1" data-flag="🇺🇸" {{ old('country_code') === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                        <option value="+44" data-flag="🇬🇧" {{ old('country_code') === '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                                        <option value="+212" data-flag="🇲🇦" {{ old('country_code') === '+212' ? 'selected' : '' }}>🇲🇦 +212</option>
                                        <option value="+225" data-flag="🇨🇮" {{ old('country_code') === '+225' ? 'selected' : '' }}>🇨🇮 +225</option>
                                        <option value="+226" data-flag="🇧🇫" {{ old('country_code') === '+226' ? 'selected' : '' }}>🇧🇫 +226</option>
                                        <option value="+227" data-flag="🇳🇪" {{ old('country_code') === '+227' ? 'selected' : '' }}>🇳🇪 +227</option>
                                        <option value="+228" data-flag="🇹🇬" {{ old('country_code') === '+228' ? 'selected' : '' }}>🇹🇬 +228</option>
                                        <option value="+229" data-flag="🇧🇯" {{ old('country_code') === '+229' ? 'selected' : '' }}>🇧🇯 +229</option>
                                        <option value="+230" data-flag="🇲🇺" {{ old('country_code') === '+230' ? 'selected' : '' }}>🇲🇺 +230</option>
                                        <option value="+231" data-flag="🇱🇷" {{ old('country_code') === '+231' ? 'selected' : '' }}>🇱🇷 +231</option>
                                        <option value="+234" data-flag="🇳🇬" {{ old('country_code') === '+234' ? 'selected' : '' }}>🇳🇬 +234</option>
                                        <option value="+237" data-flag="🇨🇲" {{ old('country_code') === '+237' ? 'selected' : '' }}>🇨🇲 +237</option>
                                        <option value="+243" data-flag="🇨🇩" {{ old('country_code') === '+243' ? 'selected' : '' }}>🇨🇩 +243</option>
                                        <option value="+250" data-flag="🇷🇼" {{ old('country_code') === '+250' ? 'selected' : '' }}>🇷🇼 +250</option>
                                        <option value="+254" data-flag="🇰🇪" {{ old('country_code') === '+254' ? 'selected' : '' }}>🇰🇪 +254</option>
                                        <option value="+255" data-flag="🇹🇿" {{ old('country_code') === '+255' ? 'selected' : '' }}>🇹🇿 +255</option>
                                        <option value="+256" data-flag="🇺🇬" {{ old('country_code') === '+256' ? 'selected' : '' }}>🇺🇬 +256</option>
                                        <option value="+257" data-flag="🇧🇮" {{ old('country_code') === '+257' ? 'selected' : '' }}>🇧🇮 +257</option>
                                        <option value="+261" data-flag="🇲🇬" {{ old('country_code') === '+261' ? 'selected' : '' }}>🇲🇬 +261</option>
                                        <option value="+262" data-flag="🇷🇪" {{ old('country_code') === '+262' ? 'selected' : '' }}>🇷🇪 +262</option>
                                        <option value="+269" data-flag="🇰🇲" {{ old('country_code') === '+269' ? 'selected' : '' }}>🇰🇲 +269</option>
                                        <option value="+32" data-flag="🇧🇪" {{ old('country_code') === '+32' ? 'selected' : '' }}>🇧🇪 +32</option>
                                        <option value="+49" data-flag="🇩🇪" {{ old('country_code') === '+49' ? 'selected' : '' }}>🇩🇪 +49</option>
                                        <option value="+39" data-flag="🇮🇹" {{ old('country_code') === '+39' ? 'selected' : '' }}>🇮🇹 +39</option>
                                        <option value="+34" data-flag="🇪🇸" {{ old('country_code') === '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                                        <option value="+351" data-flag="🇵🇹" {{ old('country_code') === '+351' ? 'selected' : '' }}>🇵🇹 +351</option>
                                        <option value="+31" data-flag="🇳🇱" {{ old('country_code') === '+31' ? 'selected' : '' }}>🇳🇱 +31</option>
                                        <option value="+41" data-flag="🇨🇭" {{ old('country_code') === '+41' ? 'selected' : '' }}>🇨🇭 +41</option>
                                        <option value="+46" data-flag="🇸🇪" {{ old('country_code') === '+46' ? 'selected' : '' }}>🇸🇪 +46</option>
                                        <option value="+47" data-flag="🇳🇴" {{ old('country_code') === '+47' ? 'selected' : '' }}>🇳🇴 +47</option>
                                        <option value="+45" data-flag="🇩🇰" {{ old('country_code') === '+45' ? 'selected' : '' }}>🇩🇰 +45</option>
                                        <option value="+358" data-flag="🇫🇮" {{ old('country_code') === '+358' ? 'selected' : '' }}>🇫🇮 +358</option>
                                        <option value="+7" data-flag="🇷🇺" {{ old('country_code') === '+7' ? 'selected' : '' }}>🇷🇺 +7</option>
                                        <option value="+86" data-flag="🇨🇳" {{ old('country_code') === '+86' ? 'selected' : '' }}>🇨🇳 +86</option>
                                        <option value="+81" data-flag="🇯🇵" {{ old('country_code') === '+81' ? 'selected' : '' }}>🇯🇵 +81</option>
                                        <option value="+82" data-flag="🇰🇷" {{ old('country_code') === '+82' ? 'selected' : '' }}>🇰🇷 +82</option>
                                        <option value="+91" data-flag="🇮🇳" {{ old('country_code') === '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                                        <option value="+55" data-flag="🇧🇷" {{ old('country_code') === '+55' ? 'selected' : '' }}>🇧🇷 +55</option>
                                        <option value="+52" data-flag="🇲🇽" {{ old('country_code') === '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                                        <option value="+54" data-flag="🇦🇷" {{ old('country_code') === '+54' ? 'selected' : '' }}>🇦🇷 +54</option>
                                        <option value="+27" data-flag="🇿🇦" {{ old('country_code') === '+27' ? 'selected' : '' }}>🇿🇦 +27</option>
                                        <option value="+20" data-flag="🇪🇬" {{ old('country_code') === '+20' ? 'selected' : '' }}>🇪🇬 +20</option>
                                        <option value="+971" data-flag="🇦🇪" {{ old('country_code') === '+971' ? 'selected' : '' }}>🇦🇪 +971</option>
                                        <option value="+966" data-flag="🇸🇦" {{ old('country_code') === '+966' ? 'selected' : '' }}>🇸🇦 +966</option>
                                        <option value="+90" data-flag="🇹🇷" {{ old('country_code') === '+90' ? 'selected' : '' }}>🇹🇷 +90</option>
                                        <option value="+61" data-flag="🇦🇺" {{ old('country_code') === '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                                        <option value="+64" data-flag="🇳🇿" {{ old('country_code') === '+64' ? 'selected' : '' }}>🇳🇿 +64</option>
                                        <option value="+65" data-flag="🇸🇬" {{ old('country_code') === '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                                        <option value="+60" data-flag="🇲🇾" {{ old('country_code') === '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                                        <option value="+66" data-flag="🇹🇭" {{ old('country_code') === '+66' ? 'selected' : '' }}>🇹🇭 +66</option>
                                        <option value="+84" data-flag="🇻🇳" {{ old('country_code') === '+84' ? 'selected' : '' }}>🇻🇳 +84</option>
                                        <option value="+62" data-flag="🇮🇩" {{ old('country_code') === '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                                        <option value="+63" data-flag="🇵🇭" {{ old('country_code') === '+63' ? 'selected' : '' }}>🇵🇭 +63</option>
                                    </select>
                                    <input type="tel" name="customer_phone" id="customer_phone" 
                                        class="form-input phone-input" 
                                        placeholder="Numéro de téléphone"
                                        value="{{ old('customer_phone') }}">
                                </div>
                                @error('customer_phone')
                                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @error('country_code')
                                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p class="form-help-text">
                                    <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                                    Recevez le lien de téléchargement via WhatsApp (optionnel)
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-credit-card"></i> Méthode de paiement
                        </h3>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="wave" id="wave" required {{ old('payment_method') === 'wave' ? 'checked' : '' }}>
                                <label for="wave" class="payment-method-label">
                                    <div class="payment-method-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="payment-method-info">
                                        <div class="payment-method-name">Wave</div>
                                        <div class="payment-method-desc">Paiement mobile money (Orange Money, Free Money)</div>
                                    </div>
                                </label>
                            </div>

                            <div class="payment-method">
                                <input type="radio" name="payment_method" value="paypal" id="paypal" required {{ old('payment_method') === 'paypal' ? 'checked' : '' }}>
                                <label for="paypal" class="payment-method-label">
                                    <div class="payment-method-icon">
                                        <i class="fab fa-paypal"></i>
                                    </div>
                                    <div class="payment-method-info">
                                        <div class="payment-method-name">PayPal</div>
                                        <div class="payment-method-desc">Paiement sécurisé via PayPal</div>
                                    </div>
                                </label>
                            </div>

                        </div>
                        @error('payment_method')
                            <div style="color: #ef4444; font-size: 0.875rem; margin-top: 1rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="checkout-summary-card">
                    <h2 class="summary-title">
                        <i class="fas fa-receipt"></i> Résumé
                    </h2>
                    
                    <div class="summary-items">
                        @foreach($cartItems as $item)
                        <div class="summary-item">
                            <span class="summary-item-name">{{ $item->document->title }}</span>
                            <span class="summary-item-price">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Code Promo -->
                    <div style="padding: 1rem; background: #f8fafc; border-radius: 12px; margin-bottom: 1rem; border: 2px solid #e2e8f0;">
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="text" 
                                   id="coupon-code" 
                                   placeholder="Code promo" 
                                   style="flex: 1; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600;"
                                   value="{{ $appliedCoupon->code ?? '' }}">
                            <button type="button" 
                                    onclick="applyCoupon()" 
                                    id="apply-coupon-btn"
                                    style="padding: 10px 20px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">
                                Appliquer
                            </button>
                        </div>
                        @if($appliedCoupon)
                        <div id="coupon-success" style="margin-top: 0.75rem; padding: 0.75rem; background: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #065f46;">
                                <i class="fas fa-check-circle"></i>
                                <span style="font-weight: 700;">Code appliqué : {{ $appliedCoupon->code }}</span>
                                <span style="color: #10b981; font-weight: 700;">
                                    -{{ $appliedCoupon->type === 'percentage' ? $appliedCoupon->value . '%' : number_format($appliedCoupon->value, 0, ',', ' ') . ' FCFA' }}
                                </span>
                            </div>
                            <button type="button" onclick="removeCoupon()" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @else
                        <div id="coupon-message" style="margin-top: 0.75rem; display: none;"></div>
                        @endif
                        <input type="hidden" name="coupon_code" id="coupon-code-hidden" value="{{ $appliedCoupon->code ?? '' }}">
                    </div>

                    <!-- Réduction coupon (si appliqué) -->
                    @if($appliedCoupon)
                    <div class="summary-item" style="color: #10b981;">
                        <span class="summary-item-name">
                            <i class="fas fa-tag"></i> Réduction ({{ $appliedCoupon->code }})
                        </span>
                        <span class="summary-item-price" style="color: #10b981;">
                            -{{ $appliedCoupon->type === 'percentage' 
                                ? number_format(($total * $appliedCoupon->value) / 100, 0, ',', ' ') . ' FCFA'
                                : number_format($appliedCoupon->value, 0, ',', ' ') . ' FCFA' }}
                        </span>
                    </div>
                    @php
                        $discount = $appliedCoupon->type === 'percentage' 
                            ? ($total * $appliedCoupon->value) / 100 
                            : $appliedCoupon->value;
                        $finalTotal = max(0, $total - $discount);
                    @endphp
                    @else
                    @php $finalTotal = $total; @endphp
                    @endif

                    <div class="summary-item summary-total">
                        <span class="summary-item-name">Total</span>
                        <span class="summary-item-price" id="final-total">{{ number_format($finalTotal, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">
                        <i class="fas fa-lock"></i> Confirmer le paiement
                    </button>

                    <a href="{{ route('documents.cart') }}" class="back-to-cart">
                        <i class="fas fa-arrow-left"></i> Retour au panier
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal PayPal Ultra Moderne -->
<div id="paypal-modal" class="wave-modal-overlay" style="display: none;">
    <div class="wave-modal-container">
        <div class="wave-modal-header">
            <div class="wave-modal-icon" style="background: linear-gradient(135deg, #0070ba, #003087);">
                <i class="fab fa-paypal"></i>
            </div>
            <h2 class="wave-modal-title">Paiement PayPal</h2>
            <button class="wave-modal-close" onclick="closePayPalModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="wave-modal-body">
            <p class="wave-modal-description">
                Cliquez sur le bouton ci-dessous pour effectuer le paiement via PayPal
            </p>
            <div class="wave-modal-amount">
                <span class="wave-amount-label">Montant à payer :</span>
                <span class="wave-amount-value" id="paypal-amount-display">0 FCFA</span>
            </div>
            
            <a href="#" id="paypal-pay-button" target="_blank" class="wave-pay-button" style="background: linear-gradient(135deg, #0070ba, #003087);">
                <i class="fab fa-paypal"></i>
                <span>Payer avec PayPal</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            
            <button type="button" onclick="confirmPayPalPayment()" class="wave-confirm-button" id="paypal-confirm-button">
                <i class="fas fa-check-circle"></i>
                <span>J'ai effectué le paiement</span>
            </button>
            
            <p class="wave-modal-note">
                <i class="fas fa-info-circle"></i>
                Après avoir effectué le paiement, cliquez sur "J'ai effectué le paiement" pour confirmer
            </p>
        </div>
    </div>
</div>

<!-- Modal Wave Ultra Moderne -->
<div id="wave-modal" class="wave-modal-overlay" style="display: none;">
    <div class="wave-modal-container">
        <div class="wave-modal-header">
            <div class="wave-modal-icon">
                <i class="fas fa-wave-square"></i>
            </div>
            <h2 class="wave-modal-title">Paiement Wave</h2>
            <button class="wave-modal-close" onclick="closeWaveModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="wave-modal-body">
            <p class="wave-modal-description">
                Cliquez sur le bouton ci-dessous pour effectuer le paiement via Wave
            </p>
            <div class="wave-modal-amount">
                <span class="wave-amount-label">Montant à payer :</span>
                <span class="wave-amount-value" id="wave-amount-display">0 FCFA</span>
            </div>
            
            <a href="#" id="wave-pay-button" target="_blank" class="wave-pay-button">
                <i class="fas fa-mobile-alt"></i>
                <span>Payer avec Wave</span>
                <i class="fas fa-arrow-right"></i>
            </a>
            
            <button type="button" onclick="confirmWavePayment()" class="wave-confirm-button" id="wave-confirm-button">
                <i class="fas fa-check-circle"></i>
                <span>J'ai effectué le paiement</span>
            </button>
            
            <p class="wave-modal-note">
                <i class="fas fa-info-circle"></i>
                Après avoir effectué le paiement, cliquez sur "J'ai effectué le paiement" pour confirmer
            </p>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
    
    // Si Wave ou PayPal est sélectionné, intercepter la soumission
    if (paymentMethod === 'wave' || paymentMethod === 'paypal') {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-btn');
        const originalContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = paymentMethod === 'wave' 
            ? '<i class="fas fa-spinner fa-spin"></i> Génération du lien Wave...'
            : '<i class="fas fa-spinner fa-spin"></i> Génération du lien PayPal...';
        
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Faire un appel AJAX
        fetch('{{ route("documents.checkout.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (paymentMethod === 'wave' && data.wave_link) {
                    // Afficher le modal Wave
                    document.getElementById('wave-pay-button').href = data.wave_link;
                    document.getElementById('wave-amount-display').textContent = 
                        new Intl.NumberFormat('fr-FR').format(data.amount) + ' FCFA';
                    
                    if (data.payment_id) {
                        window.currentPaymentId = data.payment_id;
                    }
                    
                    openWaveModal();
                } else if (paymentMethod === 'paypal' && data.paypal_link) {
                    // Afficher le modal PayPal
                    document.getElementById('paypal-pay-button').href = data.paypal_link;
                    document.getElementById('paypal-amount-display').textContent = 
                        new Intl.NumberFormat('fr-FR').format(data.amount) + ' FCFA';
                    
                    if (data.payment_id) {
                        window.currentPaymentId = data.payment_id;
                    }
                    
                    openPayPalModal();
                } else {
                    alert('Erreur lors de la génération du lien de paiement. Veuillez réessayer.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }
            } else {
                alert(data.message || 'Erreur lors de la génération du lien de paiement. Veuillez réessayer.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        });
    } else {
        // Pour les autres méthodes, soumettre normalement
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
        this.submit();
    }
});

function openWaveModal() {
    document.getElementById('wave-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeWaveModal() {
    document.getElementById('wave-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Gestion des codes promo
function applyCoupon() {
    const code = document.getElementById('coupon-code').value.trim();
    const btn = document.getElementById('apply-coupon-btn');
    const messageDiv = document.getElementById('coupon-message');
    
    if (!code) {
        showCouponMessage('Veuillez entrer un code promo', 'error');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('{{ route("coupons.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            code: code,
            amount: {{ $total }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            // Appliquer le coupon
            fetch('{{ route("coupons.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ code: code })
            })
            .then(() => {
                document.getElementById('coupon-code-hidden').value = code;
                location.reload(); // Recharger pour afficher la réduction
            });
        } else {
            showCouponMessage(data.message || 'Code promo invalide', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCouponMessage('Erreur lors de la validation du code', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = 'Appliquer';
    });
}

function removeCoupon() {
    fetch('{{ route("coupons.remove") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(() => {
        location.reload();
    });
}

function showCouponMessage(message, type) {
    const messageDiv = document.getElementById('coupon-message');
    messageDiv.style.display = 'block';
    messageDiv.style.padding = '0.75rem';
    messageDiv.style.borderRadius = '8px';
    messageDiv.style.marginTop = '0.75rem';
    messageDiv.style.color = type === 'error' ? '#991b1b' : '#065f46';
    messageDiv.style.background = type === 'error' ? '#fef2f2' : '#d1fae5';
    messageDiv.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i> ${message}`;
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}

function openPayPalModal() {
    document.getElementById('paypal-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePayPalModal() {
    document.getElementById('paypal-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Fermer le modal Wave en cliquant sur l'overlay
document.getElementById('wave-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeWaveModal();
    }
});

// Fermer le modal PayPal en cliquant sur l'overlay
document.getElementById('paypal-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePayPalModal();
    }
});

// Fonction pour confirmer le paiement Wave
function confirmWavePayment() {
    if (!window.currentPaymentId) {
        alert('Erreur: ID de paiement introuvable. Veuillez réessayer.');
        return;
    }
    
    const confirmBtn = document.getElementById('wave-confirm-button');
    const originalContent = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirmation en cours...';
    
    // Rediriger vers la page de confirmation de paiement
    window.location.href = '/payment/confirm/' + window.currentPaymentId;
}

// Fonction pour confirmer le paiement PayPal
function confirmPayPalPayment() {
    if (!window.currentPaymentId) {
        alert('Erreur: ID de paiement introuvable. Veuillez réessayer.');
        return;
    }
    
    const confirmBtn = document.getElementById('paypal-confirm-button');
    const originalContent = confirmBtn.innerHTML;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirmation en cours...';
    
    // Rediriger vers la page de confirmation de paiement
    window.location.href = '/payment/confirm/' + window.currentPaymentId;
}
</script>
@endsection
@endsection
