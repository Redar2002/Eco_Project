<x-mail-layout :subject="'Confirmation de commande '.$order->number" :preheader="'Merci '.$order->customer_name.' ! Votre commande '.$order->number.' est bien enregistrée.'">
    <tr>
        <td style="padding:40px 40px 8px;text-align:center;">
            <p style="margin:0;font-size:11px;letter-spacing:4px;text-transform:uppercase;color:#F59E0B;">Commande confirmée</p>
            <h1 style="margin:12px 0 0;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:bold;color:#0F172A;">Merci, {{ \Illuminate\Support\Str::before($order->customer_name, ' ') }} !</h1>
            <p style="margin:16px 0 0;font-size:15px;line-height:24px;color:#525252;">
                Nous avons bien reçu votre commande. Notre équipe vous appellera au
                <strong style="color:#0F172A;">{{ $order->phone }}</strong> pour la confirmer avant l'expédition.
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#EFF6FF;border:1px solid #BFDBFE;">
                <tr>
                    <td style="padding:18px;text-align:center;">
                        <span style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#1E40AF;">Numéro de commande</span><br>
                        <span style="display:inline-block;margin-top:6px;font-family:Helvetica,Arial,sans-serif;font-size:24px;letter-spacing:2px;color:#0F172A;">{{ $order->number }}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:28px 40px 0;">
            <p style="margin:0 0 6px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#737373;">Récapitulatif</p>
            @include('emails.partials.items')
        </td>
    </tr>
    <tr>
        <td style="padding:28px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" width="50%" style="padding-right:12px;font-size:13px;line-height:20px;color:#525252;">
                        <p style="margin:0 0 6px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#737373;">Livraison</p>
                        <strong style="color:#0F172A;">{{ $order->customer_name }}</strong><br>
                        {{ $order->address }}<br>{{ $order->city }}
                    </td>
                    <td valign="top" width="50%" style="padding-left:12px;font-size:13px;line-height:20px;color:#525252;">
                        <p style="margin:0 0 6px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#737373;">Paiement</p>
                        {{ $order->payment_method->label() }}<br>
                        <span style="color:#737373;">Délai estimé : 24 à 72 h</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:8px 40px 44px;text-align:center;">
            @include('emails.partials.button', ['url' => $trackingUrl, 'label' => 'Suivre ma commande'])
        </td>
    </tr>
</x-mail-layout>
