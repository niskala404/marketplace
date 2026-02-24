<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('account.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('account.addresses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'province' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'rajaongkir_city_id' => ['nullable', 'integer', 'min:1'],
            'district' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'full_address' => ['required', 'string', 'max:1000'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user, $data) {
            if (!empty($data['is_default'])) {
                Address::where('user_id', $user->id)->update(['is_default' => false]);
            }

            Address::create([
                'user_id' => $user->id,
                'label' => $data['label'],
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'province' => $data['province'] ?? null,
                'city' => $data['city'] ?? null,
                'rajaongkir_city_id' => $data['rajaongkir_city_id'] ?? null,
                'district' => $data['district'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'full_address' => $data['full_address'],
                'is_default' => (bool)($data['is_default'] ?? false),
            ]);
        });

        return redirect()->route('account.addresses.index')->with('success', 'Alamat ditambahkan.');
    }

    public function edit(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        return view('account.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'province' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'rajaongkir_city_id' => ['nullable', 'integer', 'min:1'],
            'district' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'full_address' => ['required', 'string', 'max:1000'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $userId = auth()->id();

        DB::transaction(function () use ($address, $data, $userId) {
            if (!empty($data['is_default'])) {
                Address::where('user_id', $userId)->update(['is_default' => false]);
            }

            $address->update([
                'label' => $data['label'],
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'province' => $data['province'] ?? null,
                'city' => $data['city'] ?? null,
                'rajaongkir_city_id' => $data['rajaongkir_city_id'] ?? null,
                'district' => $data['district'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'full_address' => $data['full_address'],
                'is_default' => (bool)($data['is_default'] ?? false),
            ]);
        });

        return redirect()->route('account.addresses.index')->with('success', 'Alamat diperbarui.');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $address->delete();

        $user = auth()->user();
        if ($user && !$user->addresses()->where('is_default', true)->exists()) {
            $newDefault = $user->addresses()->oldest()->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Alamat dihapus.');
    }
}
