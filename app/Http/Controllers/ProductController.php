<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->available()
            ->with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->string('search').'%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->active()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'reviews' => fn ($query) => $query->latest()->with('user'),
            'comments' => fn ($query) => $query->visible()->latest()->with('user'),
        ]);

        $purchasedItem = auth()->check() ? $product->wasPurchasedBy(auth()->user()) : null;

        return view('products.show', compact('product', 'purchasedItem'));
    }
}
