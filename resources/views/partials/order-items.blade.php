{{-- Liste des articles d'une commande + totaux (réutilisée côté client et admin) --}}
<ul class="divide-y divide-ink-100">
    @foreach ($order->items as $item)
        <li class="flex items-center gap-4 p-4 sm:px-6">
            <div class="h-16 w-14 shrink-0 overflow-hidden rounded-lg bg-ink-100">
                <img src="{{ $item->product?->image_url ?? asset('images/placeholder.svg') }}" alt="" class="h-full w-full object-cover">
            </div>
            <div class="flex-1">
                @if ($item->product && $item->product->is_active && empty($admin))
                    <a href="{{ route('products.show', $item->product) }}" class="text-sm font-semibold text-ink-900 hover:text-primary">{{ $item->product_name }}</a>
                @else
                    <p class="text-sm font-semibold text-ink-900">{{ $item->product_name }}</p>
                @endif
                <p class="text-xs text-ink-500">{{ $item->quantity }} × {{ format_mad($item->unit_price) }}</p>
            </div>
            <p class="text-sm font-semibold text-ink-900">{{ format_mad($item->line_total) }}</p>
        </li>
    @endforeach
</ul>
<dl class="space-y-2 border-t border-ink-100 p-4 text-sm sm:px-6">
    <div class="flex justify-between"><dt class="text-ink-500">Sous-total</dt><dd>{{ format_mad($order->subtotal) }}</dd></div>
    <div class="flex justify-between"><dt class="text-ink-500">Livraison</dt><dd>{{ $order->shipping_fee == 0 ? 'Offerte' : format_mad($order->shipping_fee) }}</dd></div>
    <div class="flex justify-between border-t border-ink-100 pt-3 text-base font-bold text-ink-900"><dt>Total</dt><dd>{{ format_mad($order->total) }}</dd></div>
</dl>
