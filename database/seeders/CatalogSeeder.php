<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Crée 6 catégories et 30 produits réalistes (5 par catégorie) avec images SVG.
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // On repart d'un dossier d'images propre
        Storage::disk('public')->deleteDirectory('products');
        Storage::disk('public')->deleteDirectory('categories');

        foreach ($this->catalog() as $position => $data) {
            $category = Category::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'is_featured' => true,
                'position' => $position,
                'image' => PlaceholderImage::make($data['name'], $data['shape'], $data['color'], 'categories'),
            ]);

            foreach ($data['products'] as $i => [$name, $price, $salePrice, $stock, $colors, $description]) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => $stock,
                    'is_active' => true,
                    'is_featured' => $i < 2,
                ]);

                // Dates variées : certains produits sont « Nouveaux » (< 30 jours)
                $product->forceFill([
                    'created_at' => now()->subDays(fake()->randomElement([2, 5, 9, 14, 21, 45, 60, 90, 120])),
                ])->saveQuietly();

                foreach ($colors as $p => $color) {
                    $product->images()->create([
                        'path' => PlaceholderImage::make($name, $data['shape'], $color),
                        'position' => $p,
                    ]);
                }
            }
        }
    }

    /**
     * Catalogue de démonstration.
     * Format produit : [nom, prix, prix promo|null, stock, [couleurs des visuels], description]
     */
    private function catalog(): array
    {
        return [
            [
                'name' => 'T-shirts', 'shape' => 'tshirt', 'color' => '#1E3A8A',
                'description' => 'Des basiques premium en coton, coupes oversize et classiques.',
                'products' => [
                    ['T-shirt Oversize Atlas', 199, 149, 42, ['#1E3A8A', '#64748B', '#0F172A'], "T-shirt oversize en coton 240 g/m², col côtelé et épaules tombantes. Une coupe ample et structurée qui garde sa forme lavage après lavage.\n\nComposition : 100 % coton peigné. Lavage à 30 °C."],
                    ['T-shirt Essentiel Col Rond', 149, null, 80, ['#64748B', '#334155', '#BE123C'], "Le basique indispensable : coton doux, coupe droite et finitions soignées. À porter seul ou sous une surchemise.\n\nComposition : 100 % coton bio."],
                    ['T-shirt Graphique Medina', 229, null, 25, ['#F59E0B', '#0F172A'], "Imprimé exclusif inspiré des ruelles de la médina, sérigraphié à la main. Coupe regular.\n\nComposition : 100 % coton."],
                    ['T-shirt Rayé Marinière', 189, 159, 3, ['#0F172A', '#1E3A8A'], "La marinière revisitée en version streetwear, rayures tissées et coupe légèrement ample.\n\nComposition : 95 % coton, 5 % élasthanne."],
                    ['T-shirt Manches Longues Sahara', 249, null, 0, ['#B45309', '#78716C'], "Manches longues en jersey épais, teinture minérale aux tons désert. Idéal en mi-saison.\n\nComposition : 100 % coton."],
                ],
            ],
            [
                'name' => 'Sweats & Hoodies', 'shape' => 'hoodie', 'color' => '#334155',
                'description' => 'Molletons épais et hoodies confortables pour toutes les saisons.',
                'products' => [
                    ['Hoodie Heavyweight Rif', 449, 379, 30, ['#334155', '#1E3A8A', '#64748B'], "Hoodie en molleton gratté 400 g/m², capuche doublée et poche kangourou. Le confort ultime pour l'hiver.\n\nComposition : 80 % coton, 20 % polyester."],
                    ['Sweat Col Rond Casablanca', 349, null, 45, ['#0F766E', '#78716C'], "Sweat col rond brodé « Casablanca » sur la poitrine. Bords côtes et coupe regular.\n\nComposition : 100 % coton."],
                    ['Hoodie Zippé Atlas', 499, null, 18, ['#0F172A', '#64748B'], "Hoodie zippé avec fermeture métal et poches latérales. Parfait en superposition.\n\nComposition : 70 % coton, 30 % polyester."],
                    ['Sweat Oversize Essaouira', 399, 299, 12, ['#7C3AED', '#E2E8F0'], "Sweat oversize couleur lavande, molleton doux et épaules tombantes.\n\nComposition : 100 % coton bio."],
                    ['Hoodie Tech Fleece', 549, null, 4, ['#1E293B', '#0EA5E9'], "Hoodie technique léger et chaud, idéal pour le sport comme pour la ville.\n\nComposition : 66 % coton, 34 % polyester."],
                ],
            ],
            [
                'name' => 'Jeans & Pantalons', 'shape' => 'pants', 'color' => '#1D4ED8',
                'description' => 'Denim brut, cargos et joggers aux coupes actuelles.',
                'products' => [
                    ['Jean Baggy Brut', 499, null, 35, ['#1D4ED8', '#1E3A8A'], "Jean baggy en denim brut 13 oz, taille haute et jambe large. Se patine avec le temps.\n\nComposition : 100 % coton."],
                    ['Cargo Utility Kaki', 449, 359, 22, ['#4D7C0F', '#0F172A'], "Pantalon cargo à poches plaquées, cordons de serrage aux chevilles.\n\nComposition : 100 % coton ripstop."],
                    ['Jogger Molleton Essentiel', 299, null, 60, ['#334155', '#64748B'], "Jogger confortable en molleton, taille élastiquée et poches zippées.\n\nComposition : 80 % coton, 20 % polyester."],
                    ['Jean Slim Délavé', 399, null, 28, ['#60A5FA', '#1E40AF'], "Jean slim stretch délavé, coupe ajustée et confort optimal.\n\nComposition : 98 % coton, 2 % élasthanne."],
                    ['Pantalon Chino Sable', 349, 279, 16, ['#D6B98C', '#0F172A'], "Chino coupe droite couleur sable, parfait pour un look casual chic.\n\nComposition : 97 % coton, 3 % élasthanne."],
                ],
            ],
            [
                'name' => 'Vestes', 'shape' => 'jacket', 'color' => '#0F766E',
                'description' => 'Doudounes, vestes en jean et coupe-vent pour affronter la saison.',
                'products' => [
                    ['Doudoune Matelassée Toubkal', 999, 799, 14, ['#0F172A', '#0F766E', '#F59E0B'], "Doudoune matelassée déperlante, rembourrage léger et col montant. Pensée pour les soirées fraîches de l'Atlas.\n\nComposition : 100 % polyamide recyclé."],
                    ['Veste en Jean Vintage', 649, null, 20, ['#2563EB', '#1E3A8A'], "Veste en jean délavée façon vintage, boutons métal gravés.\n\nComposition : 100 % coton."],
                    ['Coupe-vent Léger Atlantique', 549, null, 9, ['#F59E0B', '#1E3A8A'], "Coupe-vent ultra-léger, capuche intégrée et poche ventrale. Se range dans sa propre poche.\n\nComposition : 100 % nylon."],
                    ['Surchemise Flanelle', 449, 359, 2, ['#B91C1C', '#334155'], "Surchemise en flanelle à carreaux, à porter ouverte sur un t-shirt.\n\nComposition : 100 % coton."],
                    ['Bomber Satin Noir', 749, null, 11, ['#0F172A', '#7C3AED'], "Bomber satiné avec doublure contrastée et bords côtelés.\n\nComposition : 100 % polyester."],
                ],
            ],
            [
                'name' => 'Sneakers', 'shape' => 'sneaker', 'color' => '#BE123C',
                'description' => 'Sneakers lifestyle et running pour un style affirmé.',
                'products' => [
                    ['Sneakers Runner Blanc', 899, 749, 25, ['#94A3B8', '#1E3A8A', '#BE123C'], "Sneakers inspirées running, semelle amortissante et tige en mesh respirant.\n\nTailles disponibles : 39 à 45."],
                    ['Sneakers Montantes Urbain', 999, null, 15, ['#0F172A', '#64748B'], "Sneakers montantes en cuir synthétique, maintien de la cheville et semelle crantée.\n\nTailles disponibles : 39 à 45."],
                    ['Baskets Classiques Toile', 349, null, 50, ['#64748B', '#BE123C'], "Baskets basses en toile, semelle vulcanisée. Un intemporel.\n\nTailles disponibles : 36 à 45."],
                    ['Sneakers Chunky Sahara', 1099, 899, 7, ['#D6B98C', '#78350F'], "Sneakers chunky à semelle épaisse, coloris désert.\n\nTailles disponibles : 37 à 44."],
                    ['Slides Confort', 199, null, 0, ['#0F172A', '#F59E0B'], "Claquettes moulées ultra-confortables, parfaites pour l'été.\n\nTailles disponibles : 38 à 45."],
                ],
            ],
            [
                'name' => 'Accessoires', 'shape' => 'accessory', 'color' => '#F59E0B',
                'description' => 'Casquettes, bobs et sacs pour compléter votre look.',
                'products' => [
                    ['Casquette Brodée Logo', 179, null, 70, ['#1E3A8A', '#0F172A', '#64748B'], "Casquette 6 panneaux en sergé de coton, logo brodé et fermeture réglable.\n\nTaille unique."],
                    ['Bob Réversible', 199, 149, 33, ['#F59E0B', '#0F766E'], "Bob réversible deux coloris, coton épais.\n\nTaille unique."],
                    ['Bonnet Côtelé', 129, null, 40, ['#BE123C', '#334155'], "Bonnet en maille côtelée, revers large.\n\nComposition : 100 % acrylique."],
                    ['Casquette Trucker Atlas', 189, null, 4, ['#0F172A', '#64748B'], "Casquette trucker avec filet respirant et patch « Atlas ».\n\nTaille unique."],
                    ['Casquette Dad Hat Sable', 159, 119, 21, ['#D6B98C', '#1E3A8A'], "Dad hat à visière incurvée, coton délavé.\n\nTaille unique."],
                ],
            ],
        ];
    }
}
