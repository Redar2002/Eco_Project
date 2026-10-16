{{-- Version texte de l'e-mail de confirmation (clients qui n'affichent pas le HTML) --}}
@php($line = str_repeat('-', 48))
TIRRAINSTYLE — CONFIRMATION DE COMMANDE

Bonjour {{ $order->customer_name }},

Merci pour votre commande ! Elle a bien été enregistrée.
Notre équipe vous appellera au {{ $order->phone }} pour la confirmer avant l'expédition.

Commande n° : {{ $order->number }}
Date        : {{ $order->created_at->translatedFormat('d F Y à H:i') }}
Statut      : {{ $order->status->label() }}

{{ $line }}
ARTICLES
{{ $line }}
@foreach ($order->items as $item)
- {{ $item->product_name }}
  {{ $item->quantity }} x {{ format_mad($item->unit_price) }} = {{ format_mad($item->line_total) }}
@endforeach
{{ $line }}
Sous-total : {{ format_mad($order->subtotal) }}
Livraison  : {{ $order->shipping_fee == 0 ? 'Offerte' : format_mad($order->shipping_fee) }}
TOTAL      : {{ format_mad($order->total) }}

ADRESSE DE LIVRAISON
{{ $order->customer_name }}
{{ $order->address }}
{{ $order->city }}, Maroc
{{ $order->phone }}

MODE DE PAIEMENT
{{ $order->payment_method->label() }} — {{ $order->payment_method->description() }}

Suivre ma commande : {{ $trackingUrl }}

Merci pour votre confiance,
L'équipe TirrainStyle

Service client : {{ $contact['phone'] }} · {{ $contact['email'] }}@if (! empty($contact['whatsapp'])) · WhatsApp {{ $contact['whatsapp'] }}@endif

{{ $contact['address'] }}
