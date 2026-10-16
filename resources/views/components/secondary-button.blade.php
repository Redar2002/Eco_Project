<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 border border-ink-950 bg-white px-7 py-3.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-ink-950 transition hover:bg-ink-950 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
