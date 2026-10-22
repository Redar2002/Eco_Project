{{--
    Fond animé « aurore » : halos de couleur en mouvement lent, grille en perspective,
    grain photo et halo lumineux qui suit la souris (variables --mx / --my du parent).
    Purement décoratif ; animations coupées si l'utilisateur préfère réduire les mouvements.
--}}
<div class="aurora pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
    {{-- Halos animés --}}
    <div class="aurora-blob aurora-blob-1"></div>
    <div class="aurora-blob aurora-blob-2"></div>
    <div class="aurora-blob aurora-blob-3"></div>
    <div class="aurora-blob aurora-blob-4"></div>

    {{-- Grille en perspective qui défile lentement --}}
    <div class="aurora-grid"></div>

    {{-- Halo qui suit la souris --}}
    <div class="aurora-spotlight"></div>

    {{-- Grain photo --}}
    <div class="aurora-grain"></div>

    {{-- Vignettage pour garder le texte lisible --}}
    <div class="absolute inset-0 bg-gradient-to-r from-[#070d24]/80 via-[#070d24]/25 to-transparent"></div>
</div>
