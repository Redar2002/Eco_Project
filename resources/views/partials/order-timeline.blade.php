{{-- Frise de suivi d'une commande --}}
@php
    use App\Enums\OrderStatus;
    $steps = [OrderStatus::Pending, OrderStatus::Confirmed, OrderStatus::Shipped, OrderStatus::Delivered];
    $currentIndex = array_search($order->status, $steps, true);
@endphp
@if ($order->status === OrderStatus::Cancelled)
    <x-alert type="error" title="Commande annulée">Cette commande a été annulée. Contactez-nous pour toute question.</x-alert>
@else
    <ol class="grid grid-cols-4">
        @foreach ($steps as $i => $step)
            @php $done = $i <= $currentIndex; @endphp
            <li class="relative flex flex-col items-center text-center">
                @if ($i > 0)
                    <span @class(['absolute right-1/2 top-5 h-px w-full', 'bg-accent' => $done, 'bg-ink-200' => ! $done])></span>
                @endif
                <span @class([
                    'relative z-10 flex h-10 w-10 items-center justify-center rounded-full ring-8 ring-white',
                    'bg-ink-950 text-accent' => $done,
                    'border border-ink-200 bg-white text-ink-400' => ! $done,
                    'shadow-gold' => $i === $currentIndex,
                ])>
                    @if ($done)<x-icon name="check" class="h-4 w-4" stroke="2.5" />@else<span class="font-display text-sm">{{ $i + 1 }}</span>@endif
                </span>
                <span @class(['mt-3 text-[10px] font-semibold uppercase tracking-[0.14em] sm:text-[11px]', 'text-ink-950' => $done, 'text-ink-400' => ! $done])>{{ $step->label() }}</span>
            </li>
        @endforeach
    </ol>
@endif
