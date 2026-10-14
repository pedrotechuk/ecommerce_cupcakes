<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    private const COMMENTS = [
        5 => 'Simplesmente maravilhoso, superou minhas expectativas!',
        4 => 'Muito bom, com certeza vou comprar de novo.',
        3 => 'Gostei, mas achei um pouco doce demais para o meu gosto.',
    ];

    public function run(): void
    {
        $deliveredItems = Order::query()
            ->where('status', 'delivered')
            ->with('items')
            ->get()
            ->flatMap(fn (Order $order) => $order->items->map(fn ($item) => [$order, $item]));

        $ratings = [5, 4, 3];

        foreach ($deliveredItems as $index => [$order, $item]) {
            $rating = $ratings[$index % count($ratings)];

            Review::query()->updateOrCreate(
                ['order_item_id' => $item->id],
                [
                    'user_id' => $order->user_id,
                    'product_id' => $item->product_id,
                    'rating' => $rating,
                    'comment' => self::COMMENTS[$rating],
                ]
            );
        }
    }
}
