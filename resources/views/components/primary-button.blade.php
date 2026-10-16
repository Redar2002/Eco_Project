<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 bg-ink-950 px-7 py-3.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-white transition duration-300 hover:bg-accent hover:text-ink-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 active:scale-[.98] disabled:opacity-50']) }}>
    {{ $slot }}
</button>
