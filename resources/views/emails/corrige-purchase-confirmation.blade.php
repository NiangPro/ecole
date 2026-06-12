<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre corrigé</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#059669,#0891b2);padding:28px 32px;color:#ffffff;">
                            <h1 style="margin:0;font-size:20px;">Merci pour votre achat 🎉</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;">
                            <p style="margin:0 0 12px;font-size:15px;">Bonjour {{ $purchase->customer_name ?: '' }},</p>
                            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
                                Votre corrigé est prêt :
                                <strong>{{ $purchase->epreuve->title }}</strong>.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 20px;">
                                <tr>
                                    <td align="center" style="border-radius:12px;background:linear-gradient(135deg,#059669,#0891b2);">
                                        <a href="{{ $downloadUrl }}" style="display:inline-block;padding:14px 28px;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;">
                                            📄 Télécharger le corrigé
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px;font-size:13px;color:#64748b;line-height:1.6;">
                                Ce lien est personnel et valable <strong>30 jours</strong>.
                                Si le bouton ne fonctionne pas, copiez ce lien :
                            </p>
                            <p style="margin:0 0 16px;font-size:12px;word-break:break-all;">
                                <a href="{{ $downloadUrl }}" style="color:#0891b2;">{{ $downloadUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px;background:#f8fafc;color:#94a3b8;font-size:12px;text-align:center;">
                            NiangProgrammeur — Épreuves &amp; Corrigés
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
