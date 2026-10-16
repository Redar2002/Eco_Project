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
                    <span @class(['absolute right-1/2 top-4 h-0.5 w-full -translate-y-1/2', 'bg-primary' => $done, 'bg-ink-200' => ! $done])></span>
                @endif
                <span @class(['relative z-10 flex h-8 w-8 items-center justify-center rounded-full ring-4 ring-white', 'bg-primary text-white' => $done, 'bg-ink-100 text-ink-400' => ! $done])>
                    @if ($done)<x-icon name="check" class="h-4 w-4" stroke="2.5" />@else<span class="text-xs font-semibold">{{ $i + 1 }}</span>@endif
                </span>
                <span @class(['mt-2 text-xs font-medium sm:text-sm', 'text-ink-900' => $done, 'text-ink-400' => ! $done])>{{ $step->label() }}</span>
            </li>
        @endforeach
    </ol>
@endif
