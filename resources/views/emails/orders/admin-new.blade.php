<x-mail-layout :subject="'Nouvelle commande '.$order->number">
    <tr>
        <td style="padding:36px 40px 0;">
            <p style="margin:0;font-size:11px;letter-spacing:4px;text-transform:uppercase;color:#C9A24B;">Nouvelle commande</p>
            <h1 style="margin:10px 0 0;font-family:Georgia,serif;font-size:26px;font-weight:normal;color:#0A0A0A;">{{ $order->number }} — {{ format_mad($order->total) }}</h1>
            <p style="margin:14px 0 0;font-size:14px;line-height:22px;color:#525252;">
                <strong style="color:#0A0A0A;">{{ $order->customer_name }}</strong> · {{ $order->phone }} · {{ $order->email }}<br>
                {{ $order->address }}, {{ $order->city }}
                @if ($order->notes)<br><em>« {{ $order->notes }} »</em>@endif
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px 40px 0;">@include('emails.partials.items')</td>
    </tr>
    <tr>
        <td style="padding:8px 40px 40px;text-align:center;">
            @include('emails.partials.button', ['url' => $adminUrl, 'label' => 'Traiter la commande'])
        </td>
    </tr>
</x-mail-layout>
