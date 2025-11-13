@extends('layouts.app')

@section('title', 'Candidature Spontanée | NiangProgrammeur')
@section('meta_description', 'Envoyez votre candidature spontanée aux entreprises et startups à la recherche de talents en développement web et technologie.')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .application-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 100px 20px 60px;
        text-align: center;
    }
    
    .application-hero h1 {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
    
    .application-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    
    .application-form {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9));
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 40px;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        color: #fff;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1rem;
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 14px 18px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }
    
    .form-textarea {
        min-height: 150px;
        resize: vertical;
    }
    
    .form-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .form-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
    }
    
    .info-box {
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .info-box h3 {
        color: #06b6d4;
        font-size: 1.3rem;
        margin-bottom: 15px;
    }
    
    .info-box ul {
        color: rgba(255, 255, 255, 0.8);
        line-height: 2;
        margin-left: 20px;
    }
</style>
@endsection

@section('content')
<section class="application-hero">
    <div class="container mx-auto">
        <h1>📝 Candidature Spontanée</h1>
        <p style="color: rgba(255, 255, 255, 0.8); font-size: 1.2rem;">Faites-vous connaître auprès des entreprises</p>
    </div>
</section>

<div class="application-container">
    <div class="info-box">
        <h3>💡 Pourquoi envoyer une candidature spontanée ?</h3>
        <ul>
            <li>Créez votre propre opportunité même si aucune offre n'est publiée</li>
            <li>Montrez votre proactivité et votre motivation</li>
            <li>Restez dans la base de données des recruteurs</li>
            <li>Augmentez vos chances d'être contacté pour de futures opportunités</li>
        </ul>
    </div>
    
    <form action="{{ route('contact.send') }}" method="POST" class="application-form">
        @csrf
        <input type="hidden" name="subject" value="Candidature Spontanée">
        
        <div class="form-group">
            <label class="form-label">Nom complet *</label>
            <input type="text" name="name" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Téléphone</label>
            <input type="tel" name="phone" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Domaine d'expertise *</label>
            <select name="expertise" class="form-select" required>
                <option value="">Sélectionnez un domaine</option>
                <option>Développement Web Frontend</option>
                <option>Développement Web Backend</option>
                <option>Développement Full Stack</option>
                <option>Développement Mobile</option>
                <option>DevOps</option>
                <option>Data Science</option>
                <option>UI/UX Design</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Niveau d'expérience *</label>
            <select name="experience" class="form-select" required>
                <option value="">Sélectionnez un niveau</option>
                <option>Débutant (0-1 an)</option>
                <option>Junior (1-3 ans)</option>
                <option>Confirmé (3-5 ans)</option>
                <option>Senior (5+ ans)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Message / Présentation *</label>
            <textarea name="message" class="form-textarea" placeholder="Présentez-vous, vos compétences, votre motivation..." required></textarea>
        </div>
        
        <button type="submit" class="form-submit">Envoyer ma candidature →</button>
    </form>
</div>
@endsection

