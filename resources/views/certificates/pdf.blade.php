<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat - {{ $formationName }}</title>
    <style>
        @page {
            margin: 0;
            size: 297mm 210mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            color: #1e293b;
        }

        .cert-page {
            width: 297mm;
            padding: 12mm;
            overflow: hidden;
        }

        .cert-card {
            position: relative;
            width: 273mm;
            height: 160mm;
            background-color: #036b46;
            background-image: linear-gradient(135deg, #052e21, #036b46 55%, #052e21);
            border-radius: 14px;
            padding: 12px;
            page-break-inside: avoid;
        }

        .cert-corner-tl,
        .cert-corner-br {
            position: absolute;
            width: 0;
            height: 0;
        }

        .cert-corner-tl {
            top: 0;
            left: 0;
            border-style: solid;
            border-width: 54px 54px 0 0;
            border-color: #d4af37 transparent transparent transparent;
        }

        .cert-corner-br {
            bottom: 0;
            right: 0;
            border-style: solid;
            border-width: 0 0 54px 54px;
            border-color: transparent transparent #d4af37 transparent;
        }

        .cert-inner {
            position: relative;
            background: #fdfcf8;
            border-radius: 10px;
            border: 2px solid #d4af37;
            padding: 42px 56px 34px;
            text-align: center;
        }

        .cert-badge {
            width: 46px;
            height: 46px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background-color: #d4af37;
            background-image: linear-gradient(135deg, #f3d98a, #d4af37 55%, #a9791f);
            border: 3px solid #052e21;
            text-align: center;
            line-height: 40px;
            font-size: 18px;
            font-weight: bold;
            color: #052e21;
        }

        .cert-brand {
            font-size: 12px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #036b46;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .cert-title {
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1e293b;
            margin: 0;
        }

        .cert-subtitle {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 7px;
            text-transform: uppercase;
            color: #a9791f;
            margin-top: 8px;
        }

        .cert-divider {
            width: 260px;
            margin: 18px auto;
            border-top: 2px solid #d4af37;
            position: relative;
        }

        .cert-divider-dot {
            position: absolute;
            top: -5px;
            left: 50%;
            margin-left: -5px;
            width: 9px;
            height: 9px;
            background: #d4af37;
            transform: rotate(45deg);
        }

        .cert-presented-to {
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
            color: #475569;
            margin: 0 0 14px 0;
        }

        .cert-name {
            font-size: 28px;
            font-weight: bold;
            font-style: italic;
            color: #04AA6D;
            display: inline-block;
            padding-bottom: 8px;
            border-bottom: 2px solid #d4af37;
            min-width: 380px;
            margin-bottom: 18px;
        }

        .cert-body {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 6px;
        }

        .cert-body strong {
            color: #1e293b;
        }

        .cert-ornament {
            width: 200px;
            margin: 14px auto 4px;
            border-top: 1px solid #d4af37;
            position: relative;
            height: 1px;
        }

        .cert-ornament-dot {
            position: absolute;
            top: -4px;
            width: 7px;
            height: 7px;
            background: #d4af37;
            transform: rotate(45deg);
        }

        .cert-ornament-dot-1 { left: 10px; }
        .cert-ornament-dot-2 { left: 50%; margin-left: -3.5px; width: 9px; height: 9px; top: -5px; }
        .cert-ornament-dot-3 { right: 10px; }

        .cert-footer-table {
            width: 100%;
            margin-top: 20px;
        }

        .cert-footer-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .cert-footer-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .cert-footer-line {
            border-top: 1px dashed #a9791f;
            margin-bottom: 6px;
        }

        .cert-footer-label {
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="cert-page">
        <div class="cert-card">
            <div class="cert-corner-tl"></div>
            <div class="cert-corner-br"></div>

            <div class="cert-inner">
                <div class="cert-badge">&#10003;</div>
                <div class="cert-brand">NiangProgrammeur</div>

                <div class="cert-title">Certificat</div>
                <div class="cert-subtitle">De R&eacute;ussite</div>

                <div class="cert-divider"><div class="cert-divider-dot"></div></div>

                <div class="cert-presented-to">Ce certificat est fi&egrave;rement d&eacute;cern&eacute; &agrave;</div>
                <div class="cert-name">{{ $user->name ?? 'Etudiant' }}</div>

                <div class="cert-body">
                    Pour avoir termin&eacute; avec succ&egrave;s la formation<br>
                    <strong>{{ $formationName }}</strong>
                    @if(!is_null($certificate->score ?? null))
                        avec un score de <strong>{{ $certificate->score }}%</strong>
                    @endif
                </div>

                <div class="cert-ornament">
                    <div class="cert-ornament-dot cert-ornament-dot-1"></div>
                    <div class="cert-ornament-dot cert-ornament-dot-2"></div>
                    <div class="cert-ornament-dot cert-ornament-dot-3"></div>
                </div>

                <table class="cert-footer-table">
                    <tr>
                        <td>
                            <div class="cert-footer-value">{{ $certificate->completed_date ? $certificate->completed_date->format('d/m/Y') : now()->format('d/m/Y') }}</div>
                            <div class="cert-footer-line"></div>
                            <div class="cert-footer-label">Date d'obtention</div>
                        </td>
                        <td>
                            <div class="cert-footer-value">{{ $certificate->certificate_number }}</div>
                            <div class="cert-footer-line"></div>
                            <div class="cert-footer-label">Num&eacute;ro de certificat</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
