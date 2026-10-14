<?php

namespace App\Services;

use App\Mail\OrderConfirmationMail;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ShippingCalculator $shippingCalculator,
        private readonly CouponService $couponService,
    ) {
    }

    /**
     * @param  array{address_id: int, payment_method: string, coupon_code?: ?string}  $data
     */
    public function checkout(User $user, array $data): Order
    {
        $cart = $this->cartService->cartFor($user)->load('items.product');

        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Seu carrinho está vazio.',
            ]);
        }

        $address = Address::query()->where('user_id', $user->id)->findOrFail($data['address_id']);
        $shippingCost = $this->shippingCalculator->calculate($cart);

        return DB::transaction(function () use ($user, $data, $cart, $address, $shippingCost) {
            $productIds = $cart->items->pluck('product_id');
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0.0;
            $itemsData = [];

            foreach ($cart->items as $cartItem) {
                $product = $products->get($cartItem->product_id);

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => "O produto \"{$cartItem->product->name}\" não está mais disponível.",
                    ]);
                }

                if ($cartItem->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Estoque insuficiente para \"{$product->name}\". Disponível: {$product->stock}.",
                    ]);
                }

                $lineSubtotal = round((float) $product->price * $cartItem->quantity, 2);
                $subtotal += $lineSubtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $subtotal = round($subtotal, 2);
            $discount = 0.0;
            $coupon = null;
            $couponCode = $data['coupon_code'] ?? null;

            if ($couponCode) {
                $coupon = Coupon::query()->where('code', $couponCode)->lockForUpdate()->first();

                if (! $coupon || ! $coupon->isValidFor($subtotal)) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'Cupom inválido ou expirado.',
                    ]);
                }

                $discount = $coupon->calculateDiscount($subtotal);
            }

            $total = round($subtotal - $discount + $shippingCost, 2);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'shipping_street' => $address->street,
                'shipping_number' => $address->number,
                'shipping_city' => $address->city,
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_cost' => $shippingCost,
                'total' => $total,
            ]);

            foreach ($itemsData as $lineItem) {
                $product = $lineItem['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $lineItem['unit_price'],
                    'quantity' => $lineItem['quantity'],
                    'subtotal' => $lineItem['subtotal'],
                ]);

                $product->decrement('stock', $lineItem['quantity']);
            }

            if ($coupon) {
                $this->couponService->redeem($coupon);
            }

            $cart->items()->delete();

            Mail::to($user)->send(new OrderConfirmationMail($order));

            return $order->fresh('items');
        });
    }

    public function cancel(Order $order): Order
    {
        if (! $order->canBeCancelled()) {
            throw ValidationException::withMessages([
                'order' => 'Este pedido não pode mais ser cancelado.',
            ]);
        }

        DB::transaction(function () use ($order) {
            $order->loadMissing('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        return $order->fresh();
    }
}
