<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function cartFor(User $user): Cart
    {
        return Cart::query()->firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, Product $product, int $quantity): Cart
    {
        if (! $product->isAvailable()) {
            throw ValidationException::withMessages([
                'product' => 'Este produto não está disponível no momento.',
            ]);
        }

        $cart = $this->cartFor($user);
        $item = $cart->items()->where('product_id', $product->id)->first();
        $desiredQuantity = $quantity + ($item?->quantity ?? 0);

        $this->assertStock($product, $desiredQuantity);

        if ($item) {
            $item->update(['quantity' => $desiredQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $desiredQuantity,
            ]);
        }

        return $cart->fresh('items.product');
    }

    public function updateItemQuantity(User $user, Product $product, int $quantity): Cart
    {
        $cart = $this->cartFor($user);
        $item = $cart->items()->where('product_id', $product->id)->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();

            return $cart->fresh('items.product');
        }

        $this->assertStock($product, $quantity);
        $item->update(['quantity' => $quantity]);

        return $cart->fresh('items.product');
    }

    public function removeItem(User $user, Product $product): Cart
    {
        $cart = $this->cartFor($user);
        $cart->items()->where('product_id', $product->id)->delete();

        return $cart->fresh('items.product');
    }

    private function assertStock(Product $product, int $desiredQuantity): void
    {
        if ($desiredQuantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => "Estoque insuficiente. Disponível: {$product->stock} unidade(s).",
            ]);
        }
    }
}
