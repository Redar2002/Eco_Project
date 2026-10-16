@php
    use App\Enums\OrderStatus;
    $messages = [
        OrderStatus::Pending->value => ['Votre commande est en attente', 'Elle sera traitée très prochainement par notre équipe.'],
        OrderStatus::Confirmed->value => ['Votre commande est confirmée', 'Nous préparons votre colis avec soin. Il sera bientôt remis au livreur.'],
        OrderStatus::Shipped->value => ['Votre commande est en route', 'Votre colis a été remis au livreur. Il vous appellera avant de passer, gardez votre téléphone à portée de main.'],
        OrderStatus::Delivered->value => ['Votre commande est livrée', 'Merci pour votre confiance ! Nous espérons que vos articles vous plaisent.'],
        OrderStatus::Cancelled->value => ['Votre commande a été annulée', 'Si vous n\'êtes pas à l\'origine de cette annulation, contactez-nous rapidement.'],
    ];
    [$headline, $text] = $messages[$order->status->value];
    $cancelled = $order->status === OrderStatus::Cancelled;
    $steps = [OrderStatus::Pending, OrderStatus::Confirmed, OrderStatus::Shipped, OrderStatus::Delivered];
    $current = array_search($order->status, $steps, true);
@endphp
<x-mail-layout :subject="$headline" :preheader="$headline.' — commande '.$order->number">
    <tr>
        <td style="padding:40px 40px 8px;text-align:center;">
            <p style="margin:0;font-size:11px;letter-spacing:4px;text-transform:uppercase;color:{{ $cancelled ? '#B91C1C' : '#F59E0B' }};">Commande {{ $order->number }}</p>
            <h1 style="margin:12px 0 0;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:bold;color:#0F172A;">{{ $headline }}</h1>
            <p style="margin:16px 0 0;font-size:15px;line-height:24px;color:#525252;">Bonjour {{ \Illuminate\Support\Str::before($order->customer_name, ' ') }}, {{ lcfirst($text) }}</p>
        </td>
    </tr>

    @unless ($cancelled)
        {{-- Frise de suivi --}}
        <tr>
            <td style="padding:28px 40px 0;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        @foreach ($steps as $i => $step)
                            @php $done = $i <= $current; @endphp
                            <td width="25%" align="center" valign="top" style="padding:0 4px;">
                                <div style="height:4px;background:{{ $done ? '#F59E0B' : '#E5E5E5' }};line-height:4px;font-size:4px;">&nbsp;</div>
                                <p style="margin:10px 0 0;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:{{ $done ? '#0F172A' : '#A3A3A3' }};font-weight:{{ $i === $current ? 'bold' : 'normal' }};">{{ $step->label() }}</p>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
    @endunless

    <tr>
        <td style="padding:32px 40px 0;">
            <p style="margin:0 0 6px;font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#737373;">Votre commande</p>
            @include('emails.partials.items')
        </td>
    </tr>
    <tr>
        <td style="padding:8px 40px 44px;text-align:center;">
            @include('emails.partials.button', ['url' => $trackingUrl, 'label' => 'Voir le suivi'])
        </td>
    </tr>
</x-mail-layout>
