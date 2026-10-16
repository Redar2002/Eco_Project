<?php

if (! function_exists('format_mad')) {
    /**
     * Formate un montant en dirhams : 1299 => « 1 299,00 MAD ».
     * Des espaces insécables évitent que le montant soit coupé en fin de ligne.
     */
    function format_mad(float|int|string|null $amount): string
    {
        return number_format((float) $amount, 2, ',', "\u{00A0}")."\u{00A0}MAD";
    }
}
