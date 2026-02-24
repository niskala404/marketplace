<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::orderByDesc('is_active')->orderBy('name')->paginate(15);
        return view('admin.shipping_rates.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.shipping_rates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'province' => ['nullable','string','max:120'],
            'city' => ['nullable','string','max:120'],
            'base_fee' => ['required','integer','min:0'],
            'per_kg_fee' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        ShippingRate::create([
            'name' => $data['name'],
            'province' => $data['province'] ?: null,
            'city' => $data['city'] ?: null,
            'base_fee' => $data['base_fee'],
            'per_kg_fee' => $data['per_kg_fee'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.shipping-rates.index')->with('success','Tarif ongkir dibuat.');
    }

    public function edit(ShippingRate $shipping_rate)
    {
        return view('admin.shipping_rates.edit', ['rate' => $shipping_rate]);
    }

    public function update(Request $request, ShippingRate $shipping_rate)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'province' => ['nullable','string','max:120'],
            'city' => ['nullable','string','max:120'],
            'base_fee' => ['required','integer','min:0'],
            'per_kg_fee' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $shipping_rate->update([
            'name' => $data['name'],
            'province' => $data['province'] ?: null,
            'city' => $data['city'] ?: null,
            'base_fee' => $data['base_fee'],
            'per_kg_fee' => $data['per_kg_fee'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success','Tarif ongkir diperbarui.');
    }

    public function destroy(ShippingRate $shipping_rate)
    {
        $shipping_rate->delete();
        return back()->with('success','Tarif ongkir dihapus.');
    }
}
