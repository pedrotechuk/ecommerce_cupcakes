<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ShippingCalculator $shippingCalculator,
        private readonly OrderService $orderService,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->cartFor(auth()->user())->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Seu carrinho está vazio.');
        }

        $addresses = auth()->user()->addresses()->latest()->get();
        $shippingCost = $this->shippingCalculator->calculate($cart);

        return view('checkout.index', compact('cart', 'addresses', 'shippingCost'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->orderService->checkout(auth()->user(), $request->validated());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('orders.show', $order)->with('status', 'Pedido realizado com sucesso!');
    }
}
