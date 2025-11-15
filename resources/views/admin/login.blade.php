<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NiangProgrammeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #0a0a1a 50%, #000000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: rgba(10, 10, 26, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(6, 182, 212, 0.2);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            box-shadow: 0 30px 80px rgba(6, 182, 212, 0.3);
            border-color: rgba(6, 182, 212, 0.5);
        }
        
        .input-field {
            background: rgba(6, 182, 212, 0.05);
            border: 2px solid rgba(6, 182, 212, 0.2);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #06b6d4;
            background: rgba(6, 182, 212, 0.1);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1.5rem;
            color: #000;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
        }
        
        .logo-text {
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            font-weight: 900;
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-card w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <h1 class="logo-text">ADMIN PANEL</h1>
            <p class="text-gray-400">Connexion à l'espace administrateur</p>
        </div>
        
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input type="email" name="email" class="input-field" placeholder="admin@niangprogrammeur.com" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-lock mr-2"></i>Mot de passe
                </label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
            </button>
        </form>
    </div>
</body>
</html>
