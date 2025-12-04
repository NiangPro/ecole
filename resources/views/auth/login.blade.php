@extends('layouts.app')

@section('title', trans('app.auth.login.title') . ' | NiangProgrammeur')

@section('styles')
<style>
    .auth-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    }
    
    body:not(.dark-mode) .auth-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
    }
    
    .auth-card {
        max-width: 450px;
        width: 100%;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(6, 182, 212, 0.25);
        box-shadow: 0 20px 60px rgba(6, 182, 212, 0.15);
    }
    
    .auth-title {
        font-size: 2rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 10px;
        text-align: center;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    body:not(.dark-mode) .auth-title {
        color: rgba(30, 41, 59, 0.95);
    }
    
    .auth-subtitle {
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 30px;
    }
    
    body:not(.dark-mode) .auth-subtitle {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .auth-label {
        display: block;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    body:not(.dark-mode) .auth-label {
        color: rgba(30, 41, 59, 0.9);
    }
    
    .auth-input {
        width: 100%;
        padding: 12px 15px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-input {
        background: rgba(248, 250, 252, 0.9);
        border-color: rgba(6, 182, 212, 0.25);
        color: rgba(30, 41, 59, 0.9);
    }
    
    .auth-input:focus {
        outline: none;
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
    
    .auth-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    body:not(.dark-mode) .auth-input::placeholder {
        color: rgba(30, 41, 59, 0.5);
    }
    
    .auth-checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
    }
    
    body:not(.dark-mode) .auth-checkbox-label {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .auth-button {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .auth-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.4);
    }
    
    .auth-link-text {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .auth-link-text p {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 15px;
    }
    
    body:not(.dark-mode) .auth-link-text p {
        color: rgba(30, 41, 59, 0.7);
    }
    
    .auth-link-button {
        display: inline-block;
        padding: 12px 30px;
        background: rgba(15, 23, 42, 0.8);
        color: #06b6d4;
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .auth-link-button {
        background: rgba(248, 250, 252, 0.9);
        border-color: rgba(6, 182, 212, 0.25);
    }
    
    .auth-link-button:hover {
        transform: translateY(-2px);
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }
    
    .auth-alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .auth-alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    
    .auth-alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">{{ trans('app.auth.login.title') }}</h1>
        <p class="auth-subtitle">{{ trans('app.auth.login.subtitle') }}</p>

        @if(session('success'))
            <div class="auth-alert auth-alert-success">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="auth-alert auth-alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label class="auth-label">{{ trans('app.auth.login.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="auth-input"
                       placeholder="votre@email.com">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label class="auth-label">{{ trans('app.auth.login.password') }}</label>
                <input type="password" name="password" required 
                       class="auth-input"
                       placeholder="••••••••">
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                <label class="auth-checkbox-label">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="font-size: 0.9rem;">{{ trans('app.auth.login.remember') }}</span>
                </label>
            </div>
            
            <button type="submit" class="auth-button">
                <i class="fas fa-sign-in-alt mr-2"></i>{{ trans('app.auth.login.button') }}
            </button>
        </form>
        
        <div class="auth-link-text">
            <p>{{ trans('app.auth.login.no_account') }}</p>
            <a href="{{ route('register') }}" class="auth-link-button">
                <i class="fas fa-user-plus mr-2"></i>{{ trans('app.auth.login.create_account') }}
            </a>
        </div>
    </div>
</div>
@endsection

