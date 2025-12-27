<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'achat - {{ $purchase->document->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #06b6d4;
        }
        .header h1 {
            color: #06b6d4;
            margin: 0;
            font-size: 24px;
        }
        .success-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .document-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .document-info h2 {
            color: #1e293b;
            margin-top: 0;
            font-size: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #64748b;
            font-weight: 600;
        }
        .info-value {
            color: #1e293b;
        }
        .download-button {
            display: inline-block;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
        }
        .download-button:hover {
            background: linear-gradient(135deg, #0891b2, #0d9488);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning p {
            margin: 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✓</div>
            <h1>Achat confirmé !</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $purchase->customer_name ?? ($purchase->user->name ?? '') }},</p>
            
            <p>Votre achat a été confirmé avec succès. Vous pouvez maintenant télécharger votre document.</p>

            <div class="document-info">
                <h2>{{ $purchase->document->title }}</h2>
                <div class="info-row">
                    <span class="info-label">Prix payé :</span>
                    <span class="info-value">{{ number_format($purchase->amount_paid, 0, ',', ' ') }} {{ $purchase->currency }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date d'achat :</span>
                    <span class="info-value">{{ $purchase->purchased_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléchargements restants :</span>
                    <span class="info-value">{{ $purchase->download_limit - $purchase->download_count }} / {{ $purchase->download_limit }}</span>
                </div>
            </div>

            <div class="button-container">
                <a href="{{ $downloadUrl }}" class="download-button">
                    📥 Télécharger le document
                </a>
            </div>

            <div class="warning">
                <p><strong>⚠️ Important :</strong> Ce lien de téléchargement est valide pendant 30 jours. Vous avez droit à {{ $purchase->download_limit }} téléchargements.</p>
            </div>

            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        </div>

        <div class="footer">
            <p>Merci pour votre achat !</p>
            <p><strong>{{ config('app.name') }}</strong></p>
        </div>
    </div>
</body>
</html>

