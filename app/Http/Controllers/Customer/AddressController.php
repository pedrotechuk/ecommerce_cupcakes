<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->latest()->get();

        return view('addresses.index', compact('addresses'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = auth()->user();

        if (! empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($data);

        return back()->with('status', 'Endereço adicionado.');
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorizeOwnership($address);

        $data = $request->validated();

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('status', 'Endereço atualizado.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorizeOwnership($address);

        $address->delete();

        return back()->with('status', 'Endereço removido.');
    }

    private function authorizeOwnership(Address $address): void
    {
        abort_unless($address->user_id === auth()->id(), 403);
    }
}
