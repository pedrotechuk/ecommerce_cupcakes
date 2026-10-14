<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()
            ->available()
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()->active()->orderBy('name')->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
