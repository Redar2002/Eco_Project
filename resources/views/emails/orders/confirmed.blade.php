{{-- E-mail de confirmation de commande (version HTML). Version texte : confirmed-text.blade.php --}}
<x-mail-layout :subject="'Confirmation de votre commande n° '.$order->number" :preheader="'Merci '.$order->customer_name.' ! Votre commande n° '.$order->number.' du '.$order->created_at->translatedFormat('d F Y').' est bien enregistrée.'">
    <tr>
        <td class="px" style="padding:40px 40px 8px;text-align:center;">
            <p style="margin:0;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#D97706;">Commande enregistrée</p>
            <h1 class="h1" style="margin:10px 0 0;font-size:28px;line-height:34px;font-weight:bold;color:#0F172A;">Merci pour votre commande, {{ $order->customer_name }} !</h1>
            <p style="margin:16px 0 0;font-size:15px;line-height:24px;color:#475569;">
                Nous avons bien reçu votre commande et nous la préparons avec soin. Notre équipe vous appellera au
                <strong style="color:#0F172A;">{{ $order->phone }}</strong> pour la confirmer avant l'expédition.
            </p>
        </td>
    </tr>

    {{-- Numéro, date et statut --}}
    <tr>
        <td class="px" style="padding:24px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;">
                <tr>
                    <td class="stack-box" width="40%" style="padding:16px 18px;">
                        <span style="font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#1E40AF;">Commande n°</span><br>
                        <span class="number" style="font-size:18px;font-weight:bold;color:#0F172A;">{{ $order->number }}</span>
                    </td>
                    <td class="stack-box" width="35%" style="padding:16px 18px;">
                        <span style="font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#1E40AF;">Date</span><br>
                        <span style="font-size:15px;color:#0F172A;">{{ $order->created_at->translatedFormat('d F Y à H:i') }}</span>
                    </td>
                    <td class="stack-box" width="25%" style="padding:16px 18px;">
                        <span style="font-size:11px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#1E40AF;">Statut</span><br>
                        <span style="display:inline-block;margin-top:2px;padding:3px 10px;border-radius:999px;background:#FEF3C7;font-size:12px;font-weight:bold;color:#92400E;">{{ $order->status->label() }}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Articles et montants --}}
    <tr>
        <td class="px" style="padding:28px 40px 0;">
            <p style="margin:0 0 6px;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#64748B;">Récapitulatif</p>
            @include('emails.partials.items')
        </td>
    </tr>

    {{-- Livraison et paiement --}}
    <tr>
        <td class="px" style="padding:28px 40px 0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="stack" valign="top" width="50%" style="padding-right:12px;font-size:14px;line-height:22px;color:#475569;">
                        <p style="margin:0 0 6px;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#64748B;">Adresse de livraison</p>
                        <strong style="color:#0F172A;">{{ $order->customer_name }}</strong><br>
                        {{ $order->address }}<br>{{ $order->city }}, Maroc<br>
                        {{ $order->phone }}
                        @if ($order->notes)<br><em style="color:#64748B;">« {{ $order->notes }} »</em>@endif
                    </td>
                    <td class="stack" valign="top" width="50%" style="padding-left:12px;font-size:14px;line-height:22px;color:#475569;">
                        <p style="margin:0 0 6px;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;color:#64748B;">Mode de paiement</p>
                        <strong style="color:#0F172A;">{{ $order->payment_method->label() }}</strong><br>
                        {{ $order->payment_method->description() }}<br>
                        <span style="color:#64748B;">Livraison estimée : 24 à 72 h</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="px" style="padding:8px 40px 40px;text-align:center;">
            @include('emails.partials.button', ['url' => $trackingUrl, 'label' => 'Suivre ma commande'])
            <p style="margin:24px 0 0;font-size:14px;line-height:22px;color:#475569;">
                Merci pour votre confiance et à très bientôt sur TirrainStyle !<br>
                <span style="color:#64748B;">L'équipe TirrainStyle</span>
            </p>
        </td>
    </tr>
</x-mail-layout>
