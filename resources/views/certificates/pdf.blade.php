<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat - {{ $formationName }}</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .certificate-page {
            width: 100%;
            height: 100%;
            padding: 24px;
        }

        .certificate-border {
            border: 3px solid #04AA6D;
            padding: 6px;
        }

        .certificate-inner {
            border: 1px solid #06b6d4;
            padding: 46px 60px;
            text-align: center;
        }

        .certificate-brand {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #06b6d4;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .certificate-kicker {
            font-size: 15px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 18px;
        }

        .certificate-title {
            font-size: 34px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 22px 0;
        }

        .certificate-lead {
            font-size: 14px;
            color: #475569;
            margin-bottom: 8px;
        }

        .certificate-name {
            font-size: 30px;
            font-weight: bold;
            color: #04AA6D;
            margin: 6px 0 22px 0;
            padding-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            min-width: 420px;
        }

        .certificate-lead-2 {
            font-size: 14px;
            color: #475569;
            margin-bottom: 10px;
        }

        .certificate-course {
            font-size: 22px;
            font-weight: bold;
            color: #1e293b;
            margin: 4px 0 30px 0;
        }

        .certificate-footer-table {
            width: 100%;
            margin-top: 30px;
        }

        .certificate-footer-table td {
            width: 33%;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            vertical-align: top;
        }

        .certificate-footer-table strong {
            display: block;
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .certificate-seal {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #04AA6D;
            display: inline-block;
            line-height: 64px;
            font-size: 26px;
            font-weight: bold;
            color: #04AA6D;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="certificate-page">
        <div class="certificate-border">
            <div class="certificate-inner">
                <div class="certificate-brand">NiangProgrammeur</div>
                <div class="certificate-kicker">Certificat de r&eacute;ussite</div>

                <div class="certificate-title">CERTIFICAT</div>

                <div class="certificate-lead">Ce certificat est fi&egrave;rement d&eacute;cern&eacute; &agrave;</div>
                <div class="certificate-name">{{ $user->name ?? 'Etudiant' }}</div>

                <div class="certificate-lead-2">pour avoir termin&eacute; avec succ&egrave;s la formation</div>
                <div class="certificate-course">{{ $formationName }}</div>

                <table class="certificate-footer-table">
                    <tr>
                        <td>
                            <strong>{{ $certificate->completed_date ? $certificate->completed_date->format('d/m/Y') : now()->format('d/m/Y') }}</strong>
                            Date d'obtention
                        </td>
                        <td>
                            <div class="certificate-seal">&#10003;</div>
                        </td>
                        <td>
                            <strong>{{ $certificate->certificate_number }}</strong>
                            Num&eacute;ro de certificat
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
