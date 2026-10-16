{{-- Récapitulatif des articles d'une commande pour les e-mails --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #E5E5E5;">
    @foreach ($order->items as $item)
        <tr>
            <td style="padding:14px 0;border-bottom:1px solid #F0F0F0;font-size:14px;color:#171717;">
                <strong>{{ $item->product_name }}</strong><br>
                <span style="font-size:12px;color:#737373;">{{ $item->quantity }} × {{ format_mad($item->unit_price) }}</span>
            </td>
            <td align="right" style="padding:14px 0;border-bottom:1px solid #F0F0F0;font-size:14px;color:#171717;white-space:nowrap;">{{ format_mad($item->line_total) }}</td>
        </tr>
    @endforeach
    <tr>
        <td style="padding:12px 0 4px;font-size:13px;color:#737373;">Sous-total</td>
        <td align="right" style="padding:12px 0 4px;font-size:13px;color:#171717;">{{ format_mad($order->subtotal) }}</td>
    </tr>
    <tr>
        <td style="padding:4px 0;font-size:13px;color:#737373;">Livraison</td>
        <td align="right" style="padding:4px 0;font-size:13px;color:#171717;">{{ $order->shipping_fee == 0 ? 'Offerte' : format_mad($order->shipping_fee) }}</td>
    </tr>
    <tr>
        <td style="padding:12px 0 0;font-size:16px;font-weight:bold;color:#0F172A;border-top:2px solid #0F172A;">Total à payer</td>
        <td align="right" style="padding:12px 0 0;font-size:16px;font-weight:bold;color:#0F172A;border-top:2px solid #0F172A;white-space:nowrap;">{{ format_mad($order->total) }}</td>
    </tr>
</table>
