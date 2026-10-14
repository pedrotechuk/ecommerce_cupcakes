<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category' => 'Cupcakes Clássicos', 'name' => 'Cupcake de Baunilha', 'price' => 8.50, 'stock' => 40],
            ['category' => 'Cupcakes Clássicos', 'name' => 'Cupcake de Chocolate', 'price' => 9.00, 'stock' => 40],
            ['category' => 'Cupcakes Clássicos', 'name' => 'Cupcake de Cenoura com Chocolate', 'price' => 9.00, 'stock' => 30],
            ['category' => 'Cupcakes Recheados', 'name' => 'Cupcake Recheado de Doce de Leite', 'price' => 11.00, 'stock' => 25],
            ['category' => 'Cupcakes Recheados', 'name' => 'Cupcake Recheado de Morango', 'price' => 11.50, 'stock' => 25],
            ['category' => 'Cupcakes Recheados', 'name' => 'Cupcake Recheado de Nutella', 'price' => 12.00, 'stock' => 20],
            ['category' => 'Cupcakes Veganos', 'name' => 'Cupcake Vegano de Banana', 'price' => 10.00, 'stock' => 15],
            ['category' => 'Cupcakes Veganos', 'name' => 'Cupcake Vegano de Chocolate', 'price' => 10.50, 'stock' => 15],
            ['category' => 'Cupcakes Sem Glúten', 'name' => 'Cupcake Sem Glúten de Limão', 'price' => 11.00, 'stock' => 10],
            ['category' => 'Cupcakes Sem Glúten', 'name' => 'Cupcake Sem Glúten de Coco', 'price' => 11.00, 'stock' => 10],
            ['category' => 'Edições Especiais', 'name' => 'Cupcake Red Velvet', 'price' => 13.00, 'stock' => 20],
            ['category' => 'Edições Especiais', 'name' => 'Cupcake Festivo de Confeitos', 'price' => 13.50, 'stock' => 0],
        ];

        foreach ($products as $product) {
            $category = Category::query()->where('name', $product['category'])->first();
            $slug = Str::slug($product['name']);

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'description' => "Delicioso {$product['name']}, feito artesanalmente pela Cupcakes com ingredientes selecionados.",
                    'price' => $product['price'],
                    'image' => "products/{$slug}.svg",
                    'stock' => $product['stock'],
                    'is_active' => true,
                ]
            );
        }
    }
}
