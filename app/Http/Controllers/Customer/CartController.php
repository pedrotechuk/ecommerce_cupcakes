<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ShippingCalculator;
use Illuminate\Http\JsonResponse;
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

        $cartItemsForJs = $cart->items->map(fn ($item) => [
            'product_id' => $item->product_id,
            'name' => $item->product->name,
            'image' => $item->product->image_url,
            'url' => route('products.show', $item->product),
            'update_url' => route('cart.update', $item->product),
            'destroy_url' => route('cart.destroy', $item->product),
            'price' => (float) $item->product->price,
            'max' => $item->product->stock,
            'qty' => $item->quantity,
        ])->values();

        return view('cart.index', compact('cart', 'shippingCost', 'cartItemsForJs'));
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

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = $this->cartService->updateItemQuantity(auth()->user(), $product, $data['quantity']);
        $shippingCost = $this->shippingCalculator->calculate($cart);

        if ($request->wantsJson()) {
            $item = $cart->items->firstWhere('product_id', $product->id);

            return response()->json([
                'removed' => $item === null,
                'quantity' => $item->quantity ?? 0,
                'line_total' => $item ? $item->quantity * $product->price : 0,
                'subtotal' => $cart->subtotal(),
                'shipping' => $shippingCost,
                'total' => $cart->subtotal() + $shippingCost,
            ]);
        }

        return back()->with('status', 'Carrinho atualizado.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = $this->cartService->removeItem(auth()->user(), $product);

        if ($request->wantsJson()) {
            $shippingCost = $this->shippingCalculator->calculate($cart);

            return response()->json([
                'subtotal' => $cart->subtotal(),
                'shipping' => $shippingCost,
                'total' => $cart->subtotal() + $shippingCost,
            ]);
        }

        return back()->with('status', 'Produto removido do carrinho.');
    }
}
