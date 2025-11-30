<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Non Trouvée</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts optimisé avec preload et font-display: swap -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"></noscript>
    <style>
        /* Fonts chargées via preload dans le head - pas de @import bloquant */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #16213e 100%);
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
                radial-gradient(circle at 20% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
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
            background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 30%, #8b5cf6 60%, #ec4899 100%);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 0 0 80px rgba(6, 182, 212, 0.5);
        }
        
        @keyframes shimmer {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
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
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary-error {
            background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
        }
        
        .btn-primary-error:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(6, 182, 212, 0.6);
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
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
        }
        
        .floating-element {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(6, 182, 212, 0.3);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        
        .floating-element:nth-child(1) { left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { left: 20%; animation-delay: 2s; }
        .floating-element:nth-child(3) { left: 30%; animation-delay: 4s; }
        .floating-element:nth-child(4) { left: 40%; animation-delay: 6s; }
        .floating-element:nth-child(5) { left: 50%; animation-delay: 8s; }
        .floating-element:nth-child(6) { left: 60%; animation-delay: 10s; }
        .floating-element:nth-child(7) { left: 70%; animation-delay: 12s; }
        .floating-element:nth-child(8) { left: 80%; animation-delay: 14s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 8rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-error {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Non Trouvée</h1>
        <p class="error-message">
            Désolé, la page que vous recherchez n'existe pas ou a été déplacée.<br>
            Elle a peut-être été supprimée ou l'URL est incorrecte.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn-error btn-primary-error">
                <i class="fas fa-home"></i>
                Retour à l'Accueil
            </a>
            <a href="javascript:history.back()" class="btn-error btn-secondary-error">
                <i class="fas fa-arrow-left"></i>
                Page Précédente
            </a>
        </div>
    </div>
</body>
</html>

