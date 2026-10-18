<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Quelques commandes de démonstration pour alimenter le tableau de bord.
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $customers = User::where('role', User::ROLE_CUSTOMER)->get();
        $statuses = OrderStatus::cases();

        foreach (range(1, 14) as $i) {
            $customer = $customers->random();
            $status = $statuses[array_rand($statuses)];
            $lines = $products->random(rand(1, 3));

            $subtotal = 0;
            $items = [];
            foreach ($lines as $product) {
                $qty = rand(1, 2);
                $line = $product->current_price * $qty;
                $subtotal += $line;
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->current_price,
                    'quantity' => $qty,
                    'line_total' => $line,
                ];
            }
            $shipping = $subtotal >= config('shop.free_shipping_threshold') ? 0 : config('shop.shipping_fee');

            $order = Order::factory()->create([
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'email' => $customer->email,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total' => $subtotal + $shipping,
                'status' => $status,
                'payment_status' => $status === OrderStatus::Delivered ? PaymentStatus::Paid : PaymentStatus::Unpaid,
            ]);
            $order->items()->createMany($items);

            $date = now()->subDays(rand(0, 25))->subHours(rand(0, 23));
            // Commandes de démo : considérées comme déjà confirmées (aucun e-mail ne sera envoyé)
            $order->forceFill(['created_at' => $date, 'updated_at' => $date, 'confirmation_sent_at' => $date])->saveQuietly();
        }
    }
}
