<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 bg-rouge px-7 py-3.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-rouge-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-rouge focus-visible:ring-offset-2 active:scale-[.98]']) }}>
    {{ $slot }}
</button>
