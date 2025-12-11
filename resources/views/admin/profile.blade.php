@extends('admin.layout')

@section('title', 'Mon Profil - Admin')

@section('content')
<div class="profile-admin">
    <!-- Header Section -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-header-text">
                <h1 class="profile-title">
                    <span class="profile-icon-wrapper">
                        <i class="fas fa-user-shield profile-icon"></i>
                    </span>
                    Mon Profil
                </h1>
                <p class="profile-subtitle">
                    Gérez vos informations personnelles et la sécurité de votre compte
                </p>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
    <div class="alert alert-success">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Succès !</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <div class="alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">
            <strong>Erreur !</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <div class="profile-grid">
        <!-- Sidebar Profil -->
        <div class="profile-sidebar">
            <!-- Avatar Card -->
            <div class="profile-card profile-avatar-card">
                <div class="avatar-container">
                    <div class="avatar-circle">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="profile-info">
                    <h3 class="profile-name">{{ $admin->name }}</h3>
                    <p class="profile-email">{{ $admin->email }}</p>
                    <span class="profile-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Administrateur</span>
                    </span>
                </div>
            </div>

            <!-- Info Card -->
            <div class="profile-card profile-info-card">
                <h4 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Informations</span>
                </h4>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user-tag"></i>
                            Rôle
                        </span>
                        <span class="info-value">Admin</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-check-circle"></i>
                            Statut
                        </span>
                        <span class="info-badge info-active">
                            <i class="fas fa-circle"></i>
                            Actif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-clock"></i>
                            Dernière connexion
                        </span>
                        <span class="info-value">Aujourd'hui</span>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="profile-card profile-stats-card">
                <h4 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </h4>
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="stat-icon stat-users">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Utilisateurs</span>
                            <span class="stat-number">{{ \App\Models\User::count() }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon stat-visits">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Visites (mois)</span>
                            <span class="stat-number">{{ number_format(\App\Models\Statistic::whereMonth('visit_date', \Carbon\Carbon::now()->month)->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            <!-- Informations personnelles -->
            <div class="profile-card profile-form-card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-user-edit"></i>
                        <span>Informations personnelles</span>
                    </h4>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Nom complet *
                            </label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" 
                                   class="form-input" required>
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email *
                            </label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" 
                                   class="form-input" required>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Enregistrer les modifications</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Sécurité du compte -->
            <div class="profile-card profile-form-card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-lock"></i>
                        <span>Sécurité du compte</span>
                    </h4>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    
                    <input type="hidden" name="name" value="{{ $admin->name }}">
                    <input type="hidden" name="email" value="{{ $admin->email }}">
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-key"></i>
                            Mot de passe actuel *
                        </label>
                        <input type="password" name="current_password" 
                               class="form-input" placeholder="••••••••">
                        @error('current_password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Requis uniquement si vous changez votre mot de passe
                        </p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="new_password" 
                                   class="form-input" placeholder="••••••••">
                            @error('new_password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="form-hint">Minimum 6 caractères</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password" name="new_password_confirmation" 
                                   class="form-input" placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Attention</strong>
                            <p>Après avoir changé votre mot de passe, vous devrez vous reconnecter avec le nouveau mot de passe.</p>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shield-alt"></i>
                            <span>Mettre à jour le mot de passe</span>
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Activité récente -->
            <div class="profile-card profile-activity-card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-history"></i>
                        <span>Activité récente</span>
                    </h4>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon activity-login">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Connexion au dashboard</p>
                            <p class="activity-time">Aujourd'hui à {{ date('H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon activity-settings">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Modification des paramètres</p>
                            <p class="activity-time">Il y a 2 heures</p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon activity-user">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Création d'un utilisateur</p>
                            <p class="activity-time">Hier à 15:30</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-admin {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}

/* Header */
.profile-header {
    margin-bottom: 2rem;
}

.profile-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
    border: 2px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

body.light-mode .profile-header-content {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(20, 184, 166, 0.08) 100%);
    border-color: rgba(6, 182, 212, 0.4);
}

.profile-header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.profile-header-text {
    position: relative;
    z-index: 1;
}

.profile-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.profile-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 16px;
    border: 2px solid rgba(6, 182, 212, 0.3);
}

.profile-icon {
    font-size: 1.8rem;
    color: #06b6d4;
}

.profile-subtitle {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .profile-subtitle {
    color: #64748b;
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
    position: relative;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 2px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-warning {
    background: rgba(251, 191, 36, 0.15);
    border: 2px solid rgba(251, 191, 36, 0.3);
    color: #fbbf24;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 700;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

.alert-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Grid */
.profile-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2rem;
}

/* Sidebar */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Cards */
.profile-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8));
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.3s ease;
}

body.light-mode .profile-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
}

/* Avatar Card */
.profile-avatar-card {
    text-align: center;
}

.avatar-container {
    margin-bottom: 1.5rem;
}

.avatar-circle {
    width: 120px;
    height: 120px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #06b6d4;
    border: 4px solid rgba(6, 182, 212, 0.3);
}

.profile-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    margin: 0;
}

body.light-mode .profile-name {
    color: #1e293b;
}

.profile-email {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .profile-email {
    color: #64748b;
}

.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(6, 182, 212, 0.2);
    border: 1px solid rgba(6, 182, 212, 0.4);
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #06b6d4;
    margin-top: 0.5rem;
}

/* Info Card */
.card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

body.light-mode .card-title {
    color: #1e293b;
}

.card-title i {
    color: #06b6d4;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .info-item {
    background: rgba(6, 182, 212, 0.03);
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
}

body.light-mode .info-label {
    color: #64748b;
}

.info-label i {
    color: #06b6d4;
}

.info-value {
    font-weight: 700;
    color: white;
}

body.light-mode .info-value {
    color: #1e293b;
}

.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 700;
}

.info-active {
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    color: #10b981;
}

.info-active i {
    font-size: 0.6rem;
}

/* Stats Card */
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
}

body.light-mode .stat-item {
    background: rgba(6, 182, 212, 0.03);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-users {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.stat-visits {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.stat-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .stat-label {
    color: #64748b;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
}

body.light-mode .stat-number {
    color: #1e293b;
}

/* Main Content */
.profile-main {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-form-card {
    padding: 2rem;
}

.card-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(6, 182, 212, 0.2);
}

/* Forms */
.profile-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    font-size: 0.95rem;
}

body.light-mode .form-label {
    color: #1e293b;
}

.form-label i {
    color: #06b6d4;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(6, 182, 212, 0.3);
    border-radius: 12px;
    color: white;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

body.light-mode .form-input {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(6, 182, 212, 0.3);
    color: #1e293b;
}

.form-input:focus {
    outline: none;
    border-color: #06b6d4;
    background: rgba(6, 182, 212, 0.1);
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

body.light-mode .form-input:focus {
    background: rgba(255, 255, 255, 1);
}

.form-error {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.form-hint {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

body.light-mode .form-hint {
    color: #64748b;
}

.form-hint i {
    color: #06b6d4;
}

.form-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #06b6d4, #14b8a6);
    color: white;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    border: 1px solid rgba(107, 114, 128, 0.4);
    color: white;
}

body.light-mode .btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #475569;
}

.btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
}

/* Activity Card */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(6, 182, 212, 0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

body.light-mode .activity-item {
    background: rgba(6, 182, 212, 0.03);
}

.activity-item:hover {
    background: rgba(6, 182, 212, 0.1);
    transform: translateX(4px);
}

.activity-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.activity-login {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
    color: #06b6d4;
}

.activity-settings {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2));
    color: #3b82f6;
}

.activity-user {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2));
    color: #10b981;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 700;
    color: white;
    margin: 0 0 0.25rem 0;
}

body.light-mode .activity-title {
    color: #1e293b;
}

.activity-time {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

body.light-mode .activity-time {
    color: #64748b;
}

/* Responsive */
@media (max-width: 1024px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-sidebar {
        order: 2;
    }
    
    .profile-main {
        order: 1;
    }
}

@media (max-width: 768px) {
    .profile-title {
        font-size: 1.75rem;
    }
    
    .profile-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .profile-icon {
        font-size: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
