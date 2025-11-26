<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel article - {{ $article->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 30px 20px;
        }
        .article-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            display: block;
        }
        .article-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .article-excerpt {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .article-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            font-size: 14px;
            color: #64748b;
        }
        .article-meta i {
            color: #06b6d4;
            margin-right: 5px;
        }
        .btn-read-more {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .btn-read-more:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #94a3b8;
        }
        .unsubscribe-link {
            color: #06b6d4;
            text-decoration: none;
        }
        .unsubscribe-link:hover {
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-body {
                padding: 20px 15px;
            }
            .article-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📰 Nouvel Article Publié</h1>
        </div>
        
        <div class="email-body">
            @if($coverImage)
            <img src="{{ $coverImage }}" alt="{{ $article->title }}" class="article-image" />
            @endif
            
            <h2 class="article-title">{{ $article->title }}</h2>
            
            @if($article->excerpt)
            <p class="article-excerpt">{{ $article->excerpt }}</p>
            @endif
            
            <div class="article-meta">
                @if($article->category)
                <span><i class="fas fa-folder"></i> {{ $article->category->name }}</span>
                @endif
                <span><i class="fas fa-calendar"></i> {{ $article->published_at->format('d/m/Y') }}</span>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $articleUrl }}" class="btn-read-more">Lire l'article complet →</a>
            </div>
        </div>
        
        <div class="email-footer">
            <p><strong>NiangProgrammeur</strong></p>
            <p>Formation gratuite en développement web</p>
            <p style="margin-top: 15px;">
                <a href="{{ $unsubscribeUrl }}" class="unsubscribe-link">Se désabonner de la newsletter</a>
            </p>
        </div>
    </div>
</body>
</html>

