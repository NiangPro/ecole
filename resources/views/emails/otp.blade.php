<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre code de vérification</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#04AA6D,#06b6d4);padding:28px 32px;color:#ffffff;">
                            <h1 style="margin:0;font-size:20px;">Réinitialisation de votre mot de passe</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;">
                            <p style="margin:0 0 12px;font-size:15px;">Bonjour {{ $userName }},</p>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.6;">
                                Voici votre code de vérification pour réinitialiser votre mot de passe NiangProgrammeur :
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 20px;">
                                <tr>
                                    <td align="center" style="border-radius:12px;background:#f0fdf9;border:2px dashed #04AA6D;padding:20px 40px;">
                                        <span style="font-size:36px;font-weight:800;letter-spacing:10px;color:#04AA6D;font-family:'Courier New',monospace;">{{ $otp }}</span>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px;font-size:13px;color:#64748b;line-height:1.6;">
                                Ce code est valable <strong>10 minutes</strong>. Ne le partagez avec personne.
                            </p>
                            <p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">
                                Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px;background:#f8fafc;color:#94a3b8;font-size:12px;text-align:center;">
                            NiangProgrammeur — Formation gratuite en développement web
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
