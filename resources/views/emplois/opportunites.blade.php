@extends('layouts.app')

@section('title', 'Opportunités | NiangProgrammeur')
@section('meta_description', 'Découvrez les opportunités de stage, freelance, projets et collaborations dans le domaine du développement web et de la technologie.')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .opportunities-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 100px 20px 60px;
        text-align: center;
    }
    
    .opportunities-hero h1 {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
    
    .opportunities-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    
    .opportunity-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    
    .opportunity-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
    }
    
    .opportunity-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .opportunity-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }
    
    .opportunity-type {
        color: #06b6d4;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .opportunity-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .opportunity-badge {
        padding: 6px 14px;
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .opportunity-description {
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.8;
        margin: 20px 0;
    }
    
    .opportunity-apply {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s ease;
        margin-top: 20px;
    }
    
    .opportunity-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
    }
</style>
@endsection

@section('content')
<section class="opportunities-hero">
    <div class="container mx-auto">
        <h1>⭐ Opportunités</h1>
        <p style="color: rgba(255, 255, 255, 0.8); font-size: 1.2rem;">Stages, freelance, projets et collaborations</p>
    </div>
</section>

<div class="opportunities-container">
    <div class="opportunity-card">
        <div class="opportunity-header">
            <div>
                <h2 class="opportunity-title">Projet Freelance - Site E-commerce</h2>
                <p class="opportunity-type">Freelance</p>
            </div>
            <div class="opportunity-badges">
                <span class="opportunity-badge">Remote</span>
                <span class="opportunity-badge">Laravel</span>
                <span class="opportunity-badge">3 mois</span>
            </div>
        </div>
        <p class="opportunity-description">
            Recherche développeur freelance expérimenté en Laravel pour développer un site e-commerce complet. Budget: 1 500 000 FCFA. Début immédiat.
        </p>
        <a href="{{ route('contact') }}" class="opportunity-apply">Postuler →</a>
    </div>
    
    <div class="opportunity-card">
        <div class="opportunity-header">
            <div>
                <h2 class="opportunity-title">Stage Développement Frontend</h2>
                <p class="opportunity-type">Stage</p>
            </div>
            <div class="opportunity-badges">
                <span class="opportunity-badge">Dakar</span>
                <span class="opportunity-badge">React</span>
                <span class="opportunity-badge">6 mois</span>
            </div>
        </div>
        <p class="opportunity-description">
            Stage de 6 mois en développement frontend React dans une startup innovante. Environnement d'apprentissage avec mentorat personnalisé.
        </p>
        <a href="{{ route('contact') }}" class="opportunity-apply">Postuler →</a>
    </div>
    
    <div class="opportunity-card">
        <div class="opportunity-header">
            <div>
                <h2 class="opportunity-title">Collaboration Projet Open Source</h2>
                <p class="opportunity-type">Collaboration</p>
            </div>
            <div class="opportunity-badges">
                <span class="opportunity-badge">Remote</span>
                <span class="opportunity-badge">Open Source</span>
                <span class="opportunity-badge">Bénévolat</span>
            </div>
        </div>
        <p class="opportunity-description">
            Rejoignez notre équipe de contributeurs sur un projet open source innovant. Parfait pour développer votre portfolio et votre réseau.
        </p>
        <a href="{{ route('contact') }}" class="opportunity-apply">Rejoindre →</a>
    </div>
    
    <div class="opportunity-card">
        <div class="opportunity-header">
            <div>
                <h2 class="opportunity-title">Projet Part-time - Application Mobile</h2>
                <p class="opportunity-type">Part-time</p>
            </div>
            <div class="opportunity-badges">
                <span class="opportunity-badge">Remote</span>
                <span class="opportunity-badge">React Native</span>
                <span class="opportunity-badge">20h/semaine</span>
            </div>
        </div>
        <p class="opportunity-description">
            Projet part-time pour développer une application mobile React Native. Flexibilité horaire, travail à distance. Parfait pour étudiants ou freelancers.
        </p>
        <a href="{{ route('contact') }}" class="opportunity-apply">Postuler →</a>
    </div>
</div>
@endsection

