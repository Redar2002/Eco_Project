<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Génère des visuels produits « placeholder » en SVG, stockés sur le disque public.
 * Aucune dépendance réseau : idéal pour les seeders et la démo.
 */
class PlaceholderImage
{
    /** Silhouettes simples (viewBox 200x200) par type de produit. */
    private const SHAPES = [
        'tshirt' => '<path d="M60 30 L85 20 Q100 38 115 20 L140 30 L176 60 L156 86 L140 76 L140 182 L60 182 L60 76 L44 86 L24 60 Z"/>',
        'hoodie' => '<path d="M68 38 Q100 2 132 38 L150 42 L182 78 L164 162 L146 156 L146 186 L54 186 L54 156 L36 162 L18 78 L50 42 Z"/><path d="M76 132 L124 132 L130 160 L70 160 Z" fill-opacity=".18" fill="#000"/>',
        'pants' => '<path d="M60 18 L140 18 L152 186 L112 186 L100 84 L88 186 L48 186 Z"/><path d="M60 18 L140 18 L141 30 L59 30 Z" fill-opacity=".18" fill="#000"/>',
        'jacket' => '<path d="M65 25 L88 18 L100 42 L112 18 L135 25 L170 50 L186 160 L160 166 L148 92 L148 186 L52 186 L52 92 L40 166 L14 160 L30 50 Z"/><path d="M99 42 L101 42 L101 186 L99 186 Z" fill-opacity=".35" fill="#000"/>',
        'sneaker' => '<path d="M18 132 Q24 96 56 90 L80 70 Q96 63 106 80 Q130 106 170 112 Q192 117 189 140 L189 150 L18 150 Z"/><rect x="16" y="150" width="175" height="13" rx="6" fill="#fff" fill-opacity=".9"/><path d="M78 86 L96 100 M86 78 L104 94" stroke="#fff" stroke-width="4" stroke-linecap="round"/>',
        'accessory' => '<path d="M34 122 Q34 54 100 54 Q166 54 166 122 Z"/><path d="M96 122 L192 122 Q192 140 150 140 L34 140 L34 122 Z" fill-opacity=".75"/><circle cx="100" cy="54" r="6" fill-opacity=".6" fill="#000"/>',
    ];

    /**
     * Crée un SVG et retourne son chemin relatif au disque « public ».
     */
    public static function make(string $label, string $shape, string $color, string $directory = 'products'): string
    {
        $svg = self::svg($label, $shape, $color);
        $path = trim($directory, '/').'/'.Str::slug($label).'-'.Str::lower(Str::random(6)).'.svg';
        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    public static function svg(string $label, string $shape, string $color): string
    {
        $shapeSvg = self::SHAPES[$shape] ?? self::SHAPES['tshirt'];
        $bg = self::tint($color, 0.88);
        $bg2 = self::tint($color, 0.75);
        $label = e(Str::limit($label, 28));

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 1000" width="800" height="1000">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$bg}"/>
      <stop offset="1" stop-color="{$bg2}"/>
    </linearGradient>
    <filter id="s" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="18" stdDeviation="18" flood-color="#0f172a" flood-opacity=".18"/>
    </filter>
  </defs>
  <rect width="800" height="1000" fill="url(#g)"/>
  <circle cx="400" cy="470" r="300" fill="#ffffff" fill-opacity=".35"/>
  <g transform="translate(160 230) scale(2.4)" fill="{$color}" filter="url(#s)">{$shapeSvg}</g>
  <text x="400" y="900" text-anchor="middle" font-family="Poppins, Arial, sans-serif" font-size="34" font-weight="600" fill="#0f172a" fill-opacity=".72">{$label}</text>
</svg>
SVG;
    }

    /** Éclaircit une couleur hexadécimale (ratio 0 = identique, 1 = blanc). */
    private static function tint(string $hex, float $ratio): string
    {
        $hex = ltrim($hex, '#');
        [$r, $g, $b] = array_map('hexdec', str_split($hex, 2));
        $mix = fn (int $c) => (int) round($c + (255 - $c) * $ratio);

        return sprintf('#%02x%02x%02x', $mix($r), $mix($g), $mix($b));
    }
}
