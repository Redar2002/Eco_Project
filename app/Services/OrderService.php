<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private CartService $cart) {}

    /**
     * Crée la commande à partir du panier.
     *
     * Tout se passe dans une transaction : les produits sont verrouillés (lockForUpdate),
     * les prix sont recalculés depuis la base et le stock est décrémenté.
     * Si un article n'est plus disponible, rien n'est enregistré.
     *
     * @param  array{customer_name: string, email?: ?string, phone: string, city: string, address: string, notes?: ?string, payment_method: string}  $data
     *
     * @throws ValidationException
     */
    public function place(array $data, ?User $user): Order
    {
        $items = $this->cart->raw();

        if (empty($items)) {
            throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
        }

        return DB::transaction(function () use ($items, $data, $user) {
            $products = Product::whereIn('id', array_keys($items))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lines = [];
            $subtotal = 0.0;

            foreach ($items as $productId => $quantity) {
                $product = $products->get($productId);

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'Un article de votre panier n\'est plus disponible. Merci de vérifier votre panier.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Stock insuffisant pour « {$product->name} » (disponible : {$product->stock}).",
                    ]);
                }

                $unitPrice = $product->current_price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;

                $lines[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];

                // Décrément atomique du stock
                $product->decrement('stock', $quantity);
            }

            $subtotal = round($subtotal, 2);
            $shipping = $this->cart->shippingFor($subtotal);

            $order = Order::create([
                'number' => Order::generateNumber(),
                'user_id' => $user?->id,
                'customer_name' => $data['customer_name'],
                'email' => $data['email'] ?? $user?->email,
                'phone' => $data['phone'],
                'city' => $data['city'],
                'address' => $data['address'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total' => round($subtotal + $shipping, 2),
                'payment_method' => PaymentMethod::from($data['payment_method']),
                'payment_status' => PaymentStatus::Unpaid,
                'status' => OrderStatus::Pending,
            ]);

            $order->items()->createMany($lines);

            return $order;
        });
    }

    /**
     * Change le statut d'une commande. Une annulation remet les articles en stock.
     */
    public function updateStatus(Order $order, OrderStatus $status): void
    {
        DB::transaction(function () use ($order, $status) {
            $previous = $order->status;

            if ($previous === $status) {
                return;
            }

            // Annulation : on réintègre le stock
            if ($status === OrderStatus::Cancelled && $previous !== OrderStatus::Cancelled) {
                $this->restock($order, +1);
            }

            // Réactivation d'une commande annulée : on reprend le stock
            if ($previous === OrderStatus::Cancelled && $status !== OrderStatus::Cancelled) {
                $this->restock($order, -1);
            }

            $order->status = $status;

            if ($status === OrderStatus::Delivered && $order->payment_method === PaymentMethod::CashOnDelivery) {
                $order->payment_status = PaymentStatus::Paid;
            }

            $order->save();
        });
    }

    private function restock(Order $order, int $direction): void
    {
        foreach ($order->items()->with('product')->get() as $item) {
            if (! $item->product) {
                continue;
            }

            if ($direction > 0) {
                $item->product->increment('stock', $item->quantity);
            } else {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'status' => "Stock insuffisant pour réactiver la commande (« {$product->name} »).",
                    ]);
                }
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
