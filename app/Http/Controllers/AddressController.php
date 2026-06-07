<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'line'       => ['required', 'string', 'max:500'],
            'city'       => ['required', 'string', 'max:100'],
            'thana'      => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = auth()->user();

        if (!empty($data['is_default'])) {
            // clear existing default
            $user->addresses()->update(['is_default' => false]);
        }

        // First address is auto-default
        if ($user->addresses()->count() === 0) {
            $data['is_default'] = true;
        }

        $user->addresses()->create($data);

        return back()->with('status', 'Address saved.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'line'       => ['required', 'string', 'max:500'],
            'city'       => ['required', 'string', 'max:100'],
            'thana'      => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($data);

        return back()->with('status', 'Address updated.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);

        $address->delete();

        return back()->with('status', 'Address removed.');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('status', 'Default address updated.');
    }
}
