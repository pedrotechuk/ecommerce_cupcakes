<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Pedidos fictícios em status variados, para popular o histórico do cliente
 * e os dashboards administrativos (que só contabilizam receita de pedidos "delivered").
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::query()->exists()) {
            return;
        }

        $customers = User::query()->where('role', 'customer')->get();
        $products = Product::query()->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $plans = [
            ['status' => 'delivered', 'days_ago' => 20],
            ['status' => 'delivered', 'days_ago' => 12],
            ['status' => 'delivered', 'days_ago' => 5],
            ['status' => 'out_for_delivery', 'days_ago' => 1],
            ['status' => 'preparing', 'days_ago' => 0],
            ['status' => 'confirmed', 'days_ago' => 0],
            ['status' => 'pending', 'days_ago' => 0],
            ['status' => 'cancelled', 'days_ago' => 8],
        ];

        foreach ($plans as $index => $plan) {
            $user = $customers[$index % $customers->count()];
            $items = $products->random(min(2, $products->count()));

            $subtotal = 0.0;
            $lineItems = [];

            foreach ($items as $product) {
                $quantity = random_int(1, 3);
                $lineSubtotal = round((float) $product->price * $quantity, 2);
                $subtotal += $lineSubtotal;

                $lineItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $shippingCost = $subtotal >= 80 ? 0 : 8;
            $total = round($subtotal + $shippingCost, 2);
            $createdAt = now()->subDays($plan['days_ago']);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'shipping_street' => 'Rua das Cerejeiras',
                'shipping_number' => (string) random_int(10, 999),
                'shipping_city' => 'São Paulo',
                'payment_method' => $index % 2 === 0 ? 'card' : 'pix',
                'status' => $plan['status'],
                'subtotal' => round($subtotal, 2),
                'discount' => 0,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($lineItems as $lineItem) {
                $order->items()->create($lineItem);
            }
        }
    }
}
