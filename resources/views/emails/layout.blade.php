{{--
    Gabarit des e-mails TirrainStyle (identité du site : bleu nuit #1E3A8A, ambre #F59E0B).
    Tables + styles en ligne pour Gmail, Outlook et Apple Mail ; media queries pour le mobile.
--}}
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; }
        a { color: #1E3A8A; }
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px { padding-left: 20px !important; padding-right: 20px !important; }
            .stack { display: block !important; width: 100% !important; padding: 0 0 18px 0 !important; }
            .stack-box { display: block !important; width: 100% !important; box-sizing: border-box; padding: 12px 18px !important; }
            .h1 { font-size: 24px !important; line-height: 30px !important; }
            .number { font-size: 20px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Helvetica,Arial,sans-serif;color:#0F172A;">
    @isset($preheader)
        {{-- Texte d'aperçu affiché dans la boîte de réception --}}
        <div style="display:none;max-height:0;overflow:hidden;opacity:0;mso-hide:all;">{{ $preheader }}</div>
    @endisset
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background:#FFFFFF;border-radius:16px;overflow:hidden;">
                    {{-- En-tête avec le logo de la boutique --}}
                    <tr>
                        <td align="center" style="background:#1E3A8A;padding:30px 24px;">
                            <a href="{{ route('home') }}" style="text-decoration:none;">
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="width:36px;height:36px;background:#FFFFFF;border-radius:10px;text-align:center;font-size:18px;font-weight:bold;color:#1E3A8A;line-height:36px;">T</td>
                                        <td style="padding-left:10px;font-size:24px;font-weight:bold;color:#FFFFFF;letter-spacing:-0.5px;">Tirrain<span style="color:#F59E0B;">Style</span></td>
                                    </tr>
                                </table>
                            </a>
                        </td>
                    </tr>

                    {{ $slot }}

                    {{-- Pied : service client --}}
                    <tr>
                        <td class="px" style="background:#0F172A;padding:28px 40px;text-align:center;">
                            <p style="margin:0;font-size:14px;line-height:22px;color:#CBD5E1;">
                                Une question ? Notre service client est là pour vous aider.
                            </p>
                            <p style="margin:10px 0 0;font-size:13px;line-height:22px;color:#94A3B8;">
                                <a href="tel:{{ preg_replace('/\s+/', '', config('shop.contact.phone')) }}" style="color:#F59E0B;text-decoration:none;">{{ config('shop.contact.phone') }}</a>
                                &nbsp;·&nbsp;
                                <a href="mailto:{{ config('shop.contact.email') }}" style="color:#F59E0B;text-decoration:none;">{{ config('shop.contact.email') }}</a>
                                @if (config('shop.contact.whatsapp'))
                                    <br>WhatsApp : <span style="color:#F59E0B;">{{ config('shop.contact.whatsapp') }}</span>
                                @endif
                            </p>
                            <p style="margin:16px 0 0;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748B;">
                                Livraison partout au Maroc · Paiement à la livraison · Retours 14 jours
                            </p>
                            <p style="margin:12px 0 0;font-size:11px;color:#475569;">&copy; {{ date('Y') }} TirrainStyle — {{ config('shop.contact.address') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
