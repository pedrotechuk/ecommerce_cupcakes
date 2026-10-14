<?php

namespace App\Services;

use App\Models\Cart;

/**
 * Regra de frete isolada e simples: taxa fixa, com frete grátis acima de um
 * valor mínimo de subtotal. Nenhuma integração externa está configurada —
 * quando houver uma transportadora/API real, basta substituir o corpo de
 * calculate() mantendo a mesma assinatura.
 */
class ShippingCalculator
{
    private const FLAT_RATE = 8.00;

    private const FREE_SHIPPING_THRESHOLD = 80.00;

    public function calculate(Cart $cart): float
    {
        $subtotal = $cart->subtotal();

        if ($subtotal <= 0) {
            return 0.0;
        }

        if ($subtotal >= self::FREE_SHIPPING_THRESHOLD) {
            return 0.0;
        }

        return self::FLAT_RATE;
    }
}
