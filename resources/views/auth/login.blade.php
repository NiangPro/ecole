@extends('layouts.app')

@section('title', 'Connexion | NiangProgrammeur')

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);">
    <div style="max-width: 450px; width: 100%; background: rgba(15, 23, 42, 0.8); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 24px; padding: 40px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);">
        <h1 style="font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 10px; text-align: center; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Connexion
        </h1>
        <p style="text-align: center; color: rgba(255, 255, 255, 0.6); margin-bottom: 30px;">
            Connectez-vous à votre compte
        </p>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
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
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; color: #fff; font-size: 1rem; transition: all 0.3s ease;"
                       placeholder="votre@email.com">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: rgba(255, 255, 255, 0.9); font-weight: 600; margin-bottom: 8px;">
                    Mot de passe
                </label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 12px 15px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 12px; color: #fff; font-size: 1rem; transition: all 0.3s ease;"
                       placeholder="••••••••">
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                <label style="display: flex; align-items: center; gap: 8px; color: rgba(255, 255, 255, 0.7); cursor: pointer;">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="font-size: 0.9rem;">Se souvenir de moi</span>
                </label>
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #fff; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;">
                <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
            </button>
        </form>
        
        <div style="text-align: center; padding-top: 20px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
            <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 15px;">
                Pas encore de compte ?
            </p>
            <a href="{{ route('register') }}" style="display: inline-block; padding: 12px 30px; background: rgba(15, 23, 42, 0.8); color: #06b6d4; border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-user-plus mr-2"></i>Créer un compte
            </a>
        </div>
    </div>
</div>
@endsection

