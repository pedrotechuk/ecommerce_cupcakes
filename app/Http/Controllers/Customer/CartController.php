<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly ShippingCalculator $shippingCalculator,
    ) {
    }

    public function index(): View
    {
        $cart = $this->cartService->cartFor(auth()->user())->load('items.product');
        $shippingCost = $this->shippingCalculator->calculate($cart);

        return view('cart.index', compact('cart', 'shippingCost'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $this->cartService->addItem(auth()->user(), $product, $data['quantity']);

        return back()->with('status', 'Produto adicionado ao carrinho.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateItemQuantity(auth()->user(), $product, $data['quantity']);

        return back()->with('status', 'Carrinho atualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cartService->removeItem(auth()->user(), $product);

        return back()->with('status', 'Produto removido do carrinho.');
    }
}
