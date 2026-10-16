{{-- Prix d'un produit, avec prix barré si promotion (prix promo en rouge). --}}
@props(['product', 'size' => 'md'])
@php
$main = ['sm' => 'text-sm', 'md' => 'text-[15px]', 'lg' => 'text-3xl'][$size];
$old = ['sm' => 'text-xs', 'md' => 'text-xs', 'lg' => 'text-lg'][$size];
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-wrap items-baseline gap-x-2.5']) }}>
    <span @class([$main, 'font-semibold tracking-tight', 'text-rouge' => $product->is_on_sale, 'text-ink-950' => ! $product->is_on_sale])>
        {{ format_mad($product->current_price) }}
    </span>
    @if ($product->is_on_sale)
        <span class="{{ $old }} text-ink-400 line-through">{{ format_mad($product->price) }}</span>
    @endif
</div>
