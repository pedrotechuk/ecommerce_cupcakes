<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    private const BODIES = [
        'Vocês entregam para o bairro Jardins?',
        'Dá para pedir sem açúcar?',
        'Esse sabor está sempre disponível ou é sazonal?',
        'Comentário de teste para moderação do administrador.',
    ];

    public function run(): void
    {
        $customers = User::query()->where('role', 'customer')->get();
        $products = Product::query()->take(4)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($products as $index => $product) {
            $product->comments()->firstOrCreate([
                'user_id' => $customers[$index % $customers->count()]->id,
                'body' => self::BODIES[$index % count(self::BODIES)],
            ], [
                'is_hidden' => $index === 3,
            ]);
        }
    }
}
