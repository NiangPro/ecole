<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="0;url={{ $redirectUrl }}">
    <title>Redirection...</title>
    <script>
        // Redirection JavaScript immédiate (fallback si la redirection HTTP ne fonctionne pas)
        (function() {
            var redirectUrl = @json($redirectUrl);
            // Rediriger immédiatement
            window.location.replace(redirectUrl);
            
            // Fallback supplémentaire après 100ms
            setTimeout(function() {
                if (window.location.href !== redirectUrl) {
                    window.location.href = redirectUrl;
                }
            }, 100);
        })();
    </script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0a0a0f;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .redirect-message {
            text-align: center;
            padding: 2rem;
        }
        .spinner {
            border: 3px solid rgba(6, 182, 212, 0.3);
            border-top: 3px solid #06b6d4;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            color: #06b6d4;
            font-size: 1rem;
        }
        .link {
            color: #14b8a6;
            text-decoration: none;
            margin-top: 1rem;
            display: inline-block;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="redirect-message">
        <div class="spinner"></div>
        <div class="message">Redirection en cours...</div>
        <a href="{{ $redirectUrl }}" class="link">Cliquez ici si la redirection ne fonctionne pas</a>
    </div>
</body>
</html>


