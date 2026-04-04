<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressStoreRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    public function store(AddressStoreRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $isFirstAddress = !$user->addresses()->exists();

        DB::transaction(function () use ($user, $data, $isFirstAddress) {
            if ($isFirstAddress || !empty($data['is_default'])) {
                Address::where('user_id', $user->id)->update(['is_default' => false]);
            }

            $this->saveAddress(new Address(['user_id' => $user->id]), $data, $isFirstAddress);
        });

        return redirect()->route('account.addresses.index')->with('success', 'Alamat ditambahkan.');
    }

    public function edit(Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        return view('account.addresses.edit', compact('address'));
    }

    public function update(AddressUpdateRequest $request, Address $address)
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $data = $request->validated();

        $userId = auth()->id();

        DB::transaction(function () use ($address, $data, $userId) {
            if (!empty($data['is_default'])) {
                Address::where('user_id', $userId)->update(['is_default' => false]);
            }

            $this->saveAddress($address, $data, (bool) ($data['is_default'] ?? false));
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

    private function saveAddress(Address $address, array $data, bool $isDefault): void
    {
        $address->fill([
            'label' => $data['label'],
            'recipient_name' => $data['recipient_name'],
            'phone' => $data['phone'],
            'province' => $data['province'],
            'city' => $data['city'],
            'rajaongkir_city_id' => $data['rajaongkir_city_id'] ?? null,
            'district' => $data['district'],
            'village' => $data['village'],
            'postal_code' => $data['postal_code'] ?? null,
            'full_address' => $data['full_address'],
            'detail_address' => $data['detail_address'] ?? null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'is_default' => $isDefault,
        ]);

        $address->save();
    }
}
