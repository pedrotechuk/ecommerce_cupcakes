<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReviewRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Product $product): RedirectResponse
    {
        $orderItem = $product->wasPurchasedBy($request->user());

        if (! $orderItem) {
            throw ValidationException::withMessages([
                'review' => 'Você só pode avaliar produtos que já comprou e ainda não avaliou.',
            ]);
        }

        $product->reviews()->create([
            'user_id' => $request->user()->id,
            'order_item_id' => $orderItem->id,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        return back()->with('status', 'Avaliação enviada. Obrigado!');
    }
}
