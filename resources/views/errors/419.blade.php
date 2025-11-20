<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session Expirée</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #0a0a0f 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
        }
        
        @keyframes backgroundMove {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, -20px) scale(1.1); }
        }
        
        .error-container {
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 40px;
            max-width: 800px;
        }
        
        .error-code {
            font-family: 'Poppins', sans-serif;
            font-size: 12rem;
            font-weight: 900;
            background: linear-gradient(135deg, #f59e0b 0%, #06b6d4 50%, #14b8a6 100%);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 0 0 80px rgba(245, 158, 11, 0.5);
        }
        
        @keyframes shimmer {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }
        
        .error-icon {
            font-size: 8rem;
            color: #f59e0b;
            margin-bottom: 30px;
            animation: fade 2s infinite;
        }
        
        @keyframes fade {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .error-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: white;
        }
        
        .error-message {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            line-height: 1.8;
        }
        
        .error-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-error {
            padding: 16px 32px;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-primary-error {
            background: linear-gradient(135deg, #f59e0b 0%, #06b6d4 100%);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        }
        
        .btn-primary-error:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(245, 158, 11, 0.6);
        }
        
        .btn-secondary-error {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary-error:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 8rem;
            }
            
            .error-icon {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="error-code">419</div>
        <h1 class="error-title">Session Expirée</h1>
        <p class="error-message">
            Votre session a expiré pour des raisons de sécurité.<br>
            Veuillez rafraîchir la page et réessayer.
        </p>
        <div class="error-actions">
            <a href="javascript:location.reload()" class="btn-error btn-primary-error">
                <i class="fas fa-redo"></i>
                Rafraîchir la Page
            </a>
            <a href="{{ route('home') }}" class="btn-error btn-secondary-error">
                <i class="fas fa-home"></i>
                Retour à l'Accueil
            </a>
        </div>
    </div>
</body>
</html>

