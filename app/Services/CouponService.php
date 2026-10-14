<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function validate(string $code, float $subtotal): Coupon
    {
        $coupon = Coupon::query()->where('code', $code)->first();

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            throw ValidationException::withMessages([
                'coupon_code' => 'Cupom inválido ou expirado.',
            ]);
        }

        return $coupon;
    }

    /**
     * Incrementa o contador de uso do cupom de forma atômica.
     * Deve ser chamado dentro da transação de checkout, com o cupom
     * já carregado via lockForUpdate() para evitar corrida entre pedidos simultâneos.
     */
    public function redeem(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
