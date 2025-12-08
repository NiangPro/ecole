@extends('layouts.app')

@section('title', trans('app.donations.title'))
@section('meta_description', trans('app.donations.meta_description'))
@section('canonical', url('/donations'))

@php
    $settings = \App\Models\SiteSetting::first();
    $paymentMethods = [];
    
    if ($settings) {
        if ($settings->wave_enabled) {
            $paymentMethods['wave'] = [
                'name' => trans('app.donations.payment_methods.wave.name'),
                'icon' => 'fas fa-mobile-alt',
                'min_amount' => 1000,
                'description' => trans('app.donations.payment_methods.wave.description')
            ];
        }
        if ($settings->paypal_enabled) {
            $paymentMethods['paypal'] = [
                'name' => trans('app.donations.payment_methods.paypal.name'),
                'icon' => 'fab fa-paypal',
                'min_amount' => 100,
                'description' => trans('app.donations.payment_methods.paypal.description')
            ];
        }
               if ($settings->stripe_enabled) {
                   $paymentMethods['stripe'] = [
                       'name' => trans('app.donations.payment_methods.stripe.name'),
                       'icon' => 'fas fa-credit-card',
                       'min_amount' => 300, // 0.50 USD ≈ 300 XOF (minimum Stripe)
                       'description' => trans('app.donations.payment_methods.stripe.description')
                   ];
               }
    }
    
    // Si aucun moyen n'est configuré, on garde Wave par défaut
    if (empty($paymentMethods)) {
        $paymentMethods['wave'] = [
            'name' => trans('app.donations.payment_methods.wave.name'),
            'icon' => 'fas fa-mobile-alt',
            'min_amount' => 1000,
            'description' => trans('app.donations.payment_methods.wave.description')
        ];
    }
@endphp

@push('styles')
<style>
    /* Page Donations - Design Ultra Moderne */
    .donations-page {
        min-height: 100vh;
        padding-top: 100px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
    }

    body:not(.dark-mode) .donations-page {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f8fafc 100%);
    }

    body.dark-mode .donations-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }

    /* Hero Section */
    .donations-hero {
        text-align: center;
        max-width: 900px;
        margin: 0 auto 80px;
        padding: 0 20px;
        position: relative;
        z-index: 2;
        animation: fadeInDown 0.8s ease;
    }

    .donations-hero-icon {
        font-size: 6rem;
        margin-bottom: 30px;
        display: inline-block;
        animation: pulse 2s ease-in-out infinite;
        filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.3));
    }

    .donations-hero h1 {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    body:not(.dark-mode) .donations-hero h1 {
        background: linear-gradient(135deg, #0891b2 0%, #0d9488 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .donations-hero p {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
        max-width: 700px;
        margin: 0 auto;
    }

    body:not(.dark-mode) .donations-hero p {
        color: rgba(30, 41, 59, 0.8);
    }

    /* Main Container */
    .donations-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    .donations-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Form Card */
    .donation-form-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px) saturate(180%);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 40px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    body:not(.dark-mode) .donation-form-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.1);
    }

    .donation-form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .donation-form-card:hover {
        transform: translateY(-4px);
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.2);
    }

    body:not(.dark-mode) .donation-form-card:hover {
        box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
    }

    .donation-form-card:hover::before {
        opacity: 1;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .donation-form-card h3 {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    body:not(.dark-mode) .donation-form-card h3 {
        color: rgba(30, 41, 59, 0.9);
    }

    .donation-form-card h3 i {
        color: #06b6d4;
        font-size: 2.25rem;
    }

    .donation-form-card > p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 30px;
        font-size: 1.05rem;
        line-height: 1.6;
    }

    body:not(.dark-mode) .donation-form-card > p {
        color: rgba(30, 41, 59, 0.7);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        color: white;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    body:not(.dark-mode) .form-label {
        color: rgba(30, 41, 59, 0.9);
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 4px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 14px 18px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    body:not(.dark-mode) .form-input,
    body:not(.dark-mode) .form-select,
    body:not(.dark-mode) .form-textarea {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.2);
        color: rgba(30, 41, 59, 0.9);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1);
    }

    body:not(.dark-mode) .form-input:focus,
    body:not(.dark-mode) .form-select:focus,
    body:not(.dark-mode) .form-textarea:focus {
        background: white;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    body:not(.dark-mode) .form-input::placeholder,
    body:not(.dark-mode) .form-textarea::placeholder {
        color: rgba(30, 41, 59, 0.5);
    }

    .form-select option {
        background: rgba(15, 23, 42, 1);
        color: white;
    }

    body:not(.dark-mode) .form-select option {
        background: white;
        color: rgba(30, 41, 59, 0.9);
    }

    .form-help {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    body:not(.dark-mode) .form-help {
        color: rgba(30, 41, 59, 0.6);
    }

    .form-help i {
        color: #06b6d4;
    }

    .form-error {
        font-size: 0.85rem;
        color: #ef4444;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: fadeInDown 0.3s ease;
    }
    
    .form-input.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        margin-top: 20px;
    }

    .form-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #06b6d4;
    }

    .form-checkbox label {
        color: rgba(255, 255, 255, 0.9);
        cursor: pointer;
        font-weight: 500;
    }

    body:not(.dark-mode) .form-checkbox label {
        color: rgba(30, 41, 59, 0.9);
    }

    /* Submit Button */
    .btn-donate {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-donate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-donate:hover::before {
        left: 100%;
    }

    .btn-donate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.4);
    }

    .btn-donate:active {
        transform: translateY(0);
    }

    /* Wall Card */
    .donation-wall-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px) saturate(180%);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 40px;
        max-height: 900px;
        overflow-y: auto;
        position: sticky;
        top: 100px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    body:not(.dark-mode) .donation-wall-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.1);
    }

    .donation-wall-card h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    body:not(.dark-mode) .donation-wall-card h3 {
        color: rgba(30, 41, 59, 0.9);
    }

    .donation-wall-card h3 i {
        color: #06b6d4;
        font-size: 2rem;
    }

    .donation-wall-card > p {
        color: rgba(255, 255, 255, 0.7);
        text-align: center;
        margin-bottom: 30px;
    }

    body:not(.dark-mode) .donation-wall-card > p {
        color: rgba(30, 41, 59, 0.7);
    }

    .donations-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .donation-item {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    body:not(.dark-mode) .donation-item {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.2);
    }

    .donation-item:hover {
        transform: translateX(5px);
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.15);
    }

    .donation-item-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
    }

    .donation-item-name {
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    body:not(.dark-mode) .donation-item-name {
        color: rgba(30, 41, 59, 0.9);
    }

    .donation-item-amount {
        color: #14b8a6;
        font-weight: 800;
        font-size: 1.5rem;
        margin-bottom: 8px;
    }

    .donation-item-message {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin-top: 10px;
        font-style: italic;
    }

    body:not(.dark-mode) .donation-item-message {
        color: rgba(30, 41, 59, 0.7);
    }

    .donation-item-date {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8rem;
        margin-top: 10px;
    }

    body:not(.dark-mode) .donation-item-date {
        color: rgba(30, 41, 59, 0.5);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255, 255, 255, 0.7);
    }

    body:not(.dark-mode) .empty-state {
        color: rgba(30, 41, 59, 0.7);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .user-donation-badge {
        margin-top: 30px;
        padding: 20px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        text-align: center;
    }

    body:not(.dark-mode) .user-donation-badge {
        background: rgba(6, 182, 212, 0.08);
        border-color: rgba(6, 182, 212, 0.25);
    }

    .user-donation-badge p {
        color: #06b6d4;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* Scrollbar */
    .donation-wall-card::-webkit-scrollbar {
        width: 8px;
    }

    .donation-wall-card::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
    }

    body:not(.dark-mode) .donation-wall-card::-webkit-scrollbar-track {
        background: rgba(6, 182, 212, 0.1);
    }

    .donation-wall-card::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 10px;
    }

    .donation-wall-card::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #14b8a6, #06b6d4);
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .donations-grid {
            grid-template-columns: 1fr;
        }

        .donation-wall-card {
            position: static;
            max-height: none;
        }

        .donations-hero h1 {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 640px) {
        .donations-page {
            padding-top: 80px;
            padding-bottom: 40px;
        }

        .donations-hero h1 {
            font-size: 2rem;
        }

        .donation-form-card,
        .donation-wall-card {
            padding: 25px;
        }
    }
</style>
@endpush

@section('content')
<!-- NOUVELLE VERSION ULTRA MODERNE - {{ now() }} -->
<div class="donations-page">
    <div class="donations-hero">
        <div class="donations-hero-icon">❤️</div>
        <h1>{{ trans('app.donations.hero_title') }}</h1>
        <p>
            {{ trans('app.donations.hero_description') }}
        </p>
    </div>

    <div class="donations-container">
        <div class="donations-grid">
            <!-- Formulaire de Donation -->
            <div class="donation-form-card">
                <h3>
                    <i class="fas fa-heart"></i>
                    {{ trans('app.donations.form_title') }}
                </h3>
                <p>
                    {{ trans('app.donations.form_description') }}
                </p>

                <form action="{{ route('payment.donation') }}" method="POST">
                    @csrf
                    @if(request()->has('ref'))
                    <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                    @endif

                    <div class="form-group">
                        <label class="form-label">
                            {{ trans('app.donations.name_label') }} <span class="required">*</span>
                        </label>
                        <input type="text" name="donor_name" required value="{{ old('donor_name', Auth::check() ? Auth::user()->name : '') }}" class="form-input" placeholder="{{ trans('app.donations.name_placeholder') }}">
                        @error('donor_name')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    @guest
                    <div class="form-group">
                        <label class="form-label">{{ trans('app.donations.email_label') }}</label>
                        <input type="email" name="donor_email" value="{{ old('donor_email') }}" class="form-input" placeholder="{{ trans('app.donations.email_placeholder') }}">
                        @error('donor_email')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    @endguest

                    <div class="form-group">
                        <label class="form-label">
                            {{ trans('app.donations.payment_method_label') }} <span class="required">*</span>
                        </label>
                        <select name="payment_method" required id="payment_method_select" class="form-select">
                            @foreach($paymentMethods as $key => $method)
                            <option value="{{ $key }}" data-min-amount="{{ $method['min_amount'] }}" data-description="{{ $method['description'] }}" data-currency="{{ $key === 'stripe' || $key === 'paypal' ? 'USD' : 'FCFA' }}" {{ old('payment_method') === $key ? 'selected' : ($loop->first ? 'selected' : '') }}>
                                {{ $method['name'] }} - {{ $method['description'] }}
                            </option>
                            @endforeach
                        </select>
                        <p class="form-help" id="payment_method_info">
                            <i class="fas fa-info-circle"></i>
                            <span id="payment_method_description"></span>
                        </p>
                        @error('payment_method')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            {{ trans('app.donations.amount_label') }} (<span id="currency_display">FCFA</span>) <span class="required">*</span>
                        </label>
                        <input type="number" name="amount" required id="donation_amount" min="100" step="100" value="{{ old('amount', 1000) }}" class="form-input" placeholder="1000">
                        <input type="hidden" name="amount_currency" id="amount_currency" value="XOF">
                        <p class="form-help" id="amount_minimum_info">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ trans('app.donations.amount_minimum') }} : 100 FCFA</span>
                        </p>
                        <p class="form-error" id="amount_validation_error" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span id="amount_validation_message"></span>
                        </p>
                        @error('amount')
                        <p class="form-error" id="server_amount_error" style="display: flex !important;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                        @error('payment_method')
                        <p class="form-error" id="server_payment_error" style="display: flex !important;">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ trans('app.donations.message_label') }}</label>
                        <textarea name="message" rows="3" class="form-textarea" placeholder="{{ trans('app.donations.message_placeholder') }}">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" name="is_anonymous" id="is_anonymous" {{ old('is_anonymous') ? 'checked' : '' }}>
                        <label for="is_anonymous">{{ trans('app.donations.anonymous_label') }}</label>
                    </div>

                    <button type="submit" class="btn-donate">
                        <i class="fas fa-heart"></i>
                        {{ trans('app.donations.submit_button') }}
                    </button>
                </form>
            </div>

            <!-- Mur des Donateurs -->
            <div class="donation-wall-card">
                <h3>
                    <i class="fas fa-heart"></i>
                    {{ trans('app.donations.donor_wall.title') }}
                </h3>
                <p>{{ trans('app.donations.donor_wall.description') }}</p>

                @if($recentDonations->count() > 0)
                <div class="donations-list">
                    @foreach($recentDonations as $donation)
                    <div class="donation-item">
                        <div class="donation-item-icon">❤️</div>
                        <div class="donation-item-name">{{ $donation->display_name }}</div>
                        <div class="donation-item-amount">{{ number_format($donation->amount, 0, ',', ' ') }} FCFA</div>
                        @if($donation->message)
                        <div class="donation-item-message">
                            "{{ Str::limit($donation->message, 80) }}"
                        </div>
                        @endif
                        <div class="donation-item-date">
                            {{ $donation->completed_at ? $donation->completed_at->format('d/m/Y') : '' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">❤️</div>
                    <p>{{ trans('app.donations.donor_wall.empty_message') }}</p>
                </div>
                @endif

                @if(isset($userDonationCount) && $userDonationCount > 0)
                <div class="user-donation-badge">
                    <p>
                        <i class="fas fa-star"></i>
                        {{ trans_choice('app.donations.donor_wall.user_badge', $userDonationCount, ['count' => $userDonationCount]) }} {{ trans('app.donations.donor_wall.user_badge_thanks') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method_select');
        const donationAmount = document.getElementById('donation_amount');
        const amountMinimumInfo = document.getElementById('amount_minimum_info');
        const paymentMethodDescription = document.getElementById('payment_method_description');
        
        const paymentMethods = @json($paymentMethods);
        const amountValidationError = document.getElementById('amount_validation_error');
        const amountValidationMessage = document.getElementById('amount_validation_message');
        const currencyDisplay = document.getElementById('currency_display');
        
        // Taux de change (approximation)
        const XOF_TO_USD = 600; // 1 USD ≈ 600 XOF
        
        function convertXOFToUSD(xof) {
            const result = xof / XOF_TO_USD;
            // Arrondir à 2 décimales pour éviter les problèmes de précision
            return Math.round(result * 100) / 100;
        }
        
        function convertUSDToXOF(usd) {
            return Math.round(usd * XOF_TO_USD);
        }
        
        function validateAmount() {
            const selectedMethod = paymentMethodSelect.value;
            const method = paymentMethods[selectedMethod];
            if (!method) {
                amountValidationError.style.display = 'none';
                return true;
            }
            
            const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const minAmount = parseInt(selectedOption.getAttribute('data-min-amount')) || method.min_amount;
            const currency = selectedOption.getAttribute('data-currency') || 'FCFA';
            // Remplacer les virgules par des points pour la conversion (format français)
            const amountStr = donationAmount.value.trim().replace(',', '.');
            const amount = parseFloat(amountStr) || 0;
            
            // Si le champ est vide, ne pas afficher d'erreur (validation HTML5 gérera le required)
            if (!amountStr || amountStr === '' || isNaN(amount)) {
                amountValidationError.style.display = 'none';
                donationAmount.style.borderColor = '';
                donationAmount.classList.remove('error');
                return true;
            }
            
            // Convertir le minimum en USD si nécessaire pour la validation
            let displayMinAmount = minAmount;
            if (currency === 'USD') {
                displayMinAmount = convertXOFToUSD(minAmount);
            }
            
            // Masquer l'erreur si le montant est valide (comparaison avec arrondi pour éviter les problèmes de précision)
            const roundedAmount = Math.round(amount * 100) / 100;
            const roundedMin = Math.round(displayMinAmount * 100) / 100;
            
            // Debug: vérifier les valeurs
            console.log('Validation:', {
                amount: amount,
                roundedAmount: roundedAmount,
                displayMinAmount: displayMinAmount,
                roundedMin: roundedMin,
                currency: currency,
                isValid: roundedAmount >= roundedMin
            });
            
            if (roundedAmount >= roundedMin) {
                amountValidationError.style.display = 'none';
                donationAmount.style.borderColor = '';
                donationAmount.classList.remove('error');
                return true;
            }
            
            // Afficher l'erreur si le montant est invalide (utiliser les valeurs arrondies pour la comparaison)
            
            if (amount > 0 && roundedAmount < roundedMin) {
                const currencySymbol = currency === 'USD' ? 'dollar' : 'FCFA';
                const formattedMin = displayMinAmount.toLocaleString('fr-FR', { 
                    minimumFractionDigits: currency === 'USD' ? 2 : 0, 
                    maximumFractionDigits: currency === 'USD' ? 2 : 0 
                });
                const minAmountText = '{{ trans('app.donations.amount_minimum') }}';
                amountValidationMessage.textContent = `${minAmountText} pour ${method.name} : ${formattedMin} ${currencySymbol}`;
                amountValidationError.style.display = 'flex';
                donationAmount.style.borderColor = '#ef4444';
                donationAmount.classList.add('error');
                return false;
            }
            
            return true;
        }
        
        function updatePaymentInfo() {
            const selectedMethod = paymentMethodSelect.value;
            const method = paymentMethods[selectedMethod];
            if (!method) return;
            
            const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const minAmount = parseInt(selectedOption.getAttribute('data-min-amount')) || method.min_amount;
            const currency = selectedOption.getAttribute('data-currency') || 'FCFA';
            
            // Mettre à jour l'affichage de la devise (afficher "dollar" pour USD)
            currencyDisplay.textContent = currency === 'USD' ? 'dollar' : currency;
            
            // Mettre à jour le champ hidden pour la devise
            document.getElementById('amount_currency').value = currency === 'USD' ? 'USD' : 'XOF';
            
            // Convertir le minimum en USD si nécessaire
            let displayMinAmount = minAmount;
            if (currency === 'USD') {
                displayMinAmount = parseFloat(convertXOFToUSD(minAmount));
            }
            
            paymentMethodDescription.textContent = method.description;
            const currencyLabel = currency === 'USD' ? 'dollar' : currency;
            const minimumText = '{{ trans('app.donations.amount_minimum') }}';
            amountMinimumInfo.querySelector('span').textContent = `${minimumText} : ${displayMinAmount.toLocaleString('fr-FR', { minimumFractionDigits: currency === 'USD' ? 2 : 0, maximumFractionDigits: currency === 'USD' ? 2 : 0 })} ${currencyLabel}`;
            
            // Mettre à jour le placeholder et le step
            if (currency === 'USD') {
                donationAmount.step = '0.01';
                donationAmount.placeholder = '1.00';
                // Convertir la valeur actuelle en USD si elle existe et est en XOF
                const currentValue = parseFloat(donationAmount.value) || 0;
                // Si la valeur actuelle est >= minAmount en XOF et > 10, c'est probablement une valeur XOF à convertir
                if (currentValue > 0 && currentValue >= minAmount && currentValue > 10) {
                    // C'est probablement en XOF (valeur élevée), convertir en USD
                    donationAmount.value = convertXOFToUSD(currentValue);
                } else if (currentValue === 0 || donationAmount.value === '' || donationAmount.value === null) {
                    // Seulement si le champ est vraiment vide, mettre le minimum
                    donationAmount.value = displayMinAmount;
                }
                // Sinon, garder la valeur saisie (déjà en USD ou autre valeur valide)
            } else {
                donationAmount.step = '100';
                donationAmount.placeholder = '1000';
            }
            
            donationAmount.min = currency === 'USD' ? displayMinAmount : minAmount;
            donationAmount.setAttribute('min', currency === 'USD' ? displayMinAmount : minAmount);
            
            // Masquer l'erreur lors du changement de méthode
            amountValidationError.style.display = 'none';
            donationAmount.style.borderColor = '';
            donationAmount.classList.remove('error');
            
            // Valider le montant actuel (mais ne pas forcer l'affichage si valide)
            const currentAmount = parseFloat(donationAmount.value) || 0;
            if (currentAmount > 0) {
                validateAmount();
            }
        }
        
        // Valider le montant lors de la saisie
        donationAmount.addEventListener('input', function() {
            // Masquer les erreurs serveur lors de la saisie
            const serverError = document.getElementById('server_amount_error');
            const serverPaymentError = document.getElementById('server_payment_error');
            if (serverError) serverError.style.display = 'none';
            if (serverPaymentError) serverPaymentError.style.display = 'none';
            // Valider et masquer l'erreur si valide
            validateAmount();
        });
        
        donationAmount.addEventListener('blur', function() {
            validateAmount();
        });
        
        donationAmount.addEventListener('change', function() {
            validateAmount();
        });
        
        // Valider le montant avant soumission et convertir en XOF si nécessaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const currency = selectedOption.getAttribute('data-currency') || 'FCFA';
            
            // Valider d'abord avec la devise actuelle
            const isValid = validateAmount();
            if (!isValid) {
                e.preventDefault();
                donationAmount.focus();
                donationAmount.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
            
            // Nouvelle approche : envoyer le montant dans sa devise d'origine
            // Le serveur se chargera de la conversion et validation
            if (currency === 'USD') {
                // Mettre à jour le champ hidden pour indiquer la devise
                document.getElementById('amount_currency').value = 'USD';
            } else {
                document.getElementById('amount_currency').value = 'XOF';
            }
        });
        
        paymentMethodSelect.addEventListener('change', function() {
            // Masquer les erreurs serveur lors du changement
            const serverError = document.getElementById('server_amount_error');
            const serverPaymentError = document.getElementById('server_payment_error');
            if (serverError) serverError.style.display = 'none';
            if (serverPaymentError) serverPaymentError.style.display = 'none';
            updatePaymentInfo();
            validateAmount();
        });
        
        // Afficher les erreurs serveur si présentes au chargement
        @if($errors->has('amount') || $errors->has('payment_method'))
            setTimeout(function() {
                const amountInput = document.getElementById('donation_amount');
                const serverError = document.getElementById('server_amount_error');
                const serverPaymentError = document.getElementById('server_payment_error');
                
                if (amountInput) {
                    amountInput.classList.add('error');
                    amountInput.style.borderColor = '#ef4444';
                }
                
                // S'assurer que les erreurs serveur sont visibles
                if (serverError) {
                    serverError.style.display = 'flex';
                }
                if (serverPaymentError) {
                    serverPaymentError.style.display = 'flex';
                }
                
                // Scroller vers le champ en erreur
                if (amountInput) {
                    amountInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        @endif
        
        updatePaymentInfo(); // Initialiser
    });
</script>
@endsection
