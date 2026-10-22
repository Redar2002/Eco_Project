{{-- Particules flottantes d'arrière-plan (purement décoratives) --}}
<div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
    @foreach (\App\Support\ProductWorlds::particles($colors) as $p)
        <span class="showcase-particle"
              style="left: {{ $p['x'] }}%; top: {{ $p['y'] }}%; width: {{ $p['size'] }}px; height: {{ $p['size'] }}px; background: {{ $p['color'] }}; box-shadow: 0 0 {{ $p['size'] * 2 }}px {{ $p['color'] }}; animation-duration: {{ $p['duration'] }}s; animation-delay: {{ $p['delay'] }}s; --drift: {{ $p['drift'] }}px;"></span>
    @endforeach
</div>
