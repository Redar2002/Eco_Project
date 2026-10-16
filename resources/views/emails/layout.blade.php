{{--
    Gabarit des e-mails TirrainStyle (noir, blanc, or).
    Tables + styles en ligne pour une compatibilité maximale (Gmail, Outlook, mobile).
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $subject ?? config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#F4F1EA;font-family:Helvetica,Arial,sans-serif;color:#171717;">
    @isset($preheader)
        <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $preheader }}</div>
    @endisset
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F4F1EA;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;background:#FFFFFF;">
                    {{-- En-tête --}}
                    <tr>
                        <td align="center" style="background:#0A0A0A;padding:34px 24px 30px;">
                            <div style="font-family:Georgia,'Times New Roman',serif;font-size:26px;letter-spacing:6px;color:#FFFFFF;">TIRRAIN<span style="color:#C9A24B;">STYLE</span></div>
                            <div style="margin:14px auto 0;width:48px;height:1px;background:#C9A24B;line-height:1px;font-size:1px;">&nbsp;</div>
                        </td>
                    </tr>

                    {{ $slot }}

                    {{-- Pied --}}
                    <tr>
                        <td style="background:#0A0A0A;padding:28px 32px;text-align:center;">
                            <p style="margin:0;font-size:13px;line-height:20px;color:#A3A3A3;">
                                Une question ? Répondez simplement à cet e-mail ou appelez-nous au
                                <span style="color:#C9A24B;">{{ config('shop.contact.phone') }}</span>.
                            </p>
                            <p style="margin:14px 0 0;font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#737373;">
                                Livraison partout au Maroc · Paiement à la livraison · Retours 14 jours
                            </p>
                            <p style="margin:14px 0 0;font-size:11px;color:#525252;">&copy; {{ date('Y') }} TirrainStyle — {{ config('shop.contact.address') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
