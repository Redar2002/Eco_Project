<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomElement([299, 450, 598, 749, 1099]);
        $shipping = $subtotal >= config('shop.free_shipping_threshold') ? 0 : config('shop.shipping_fee');

        return [
            'number' => Order::generateNumber(),
            'user_id' => null,
            'customer_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '06'.fake()->numerify('########'),
            'city' => fake()->randomElement(config('shop.cities')),
            'address' => fake()->streetAddress(),
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping,
            'total' => $subtotal + $shipping,
            'payment_method' => PaymentMethod::CashOnDelivery,
            'payment_status' => PaymentStatus::Unpaid,
            'status' => OrderStatus::Pending,
        ];
    }
}
