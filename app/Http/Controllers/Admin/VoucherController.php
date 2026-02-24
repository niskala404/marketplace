<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('shop')->latest()->paginate(15);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $shops = Shop::orderBy('name')->get();
        return view('admin.vouchers.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:40','unique:vouchers,code'],
            'name' => ['required','string','max:120'],
            'shop_id' => ['nullable','integer','exists:shops,id'],
            'type' => ['required','in:fixed,percent,shipping'],
            'value' => ['required','integer','min:1'],
            'min_subtotal' => ['nullable','integer','min:0'],
            'max_discount' => ['nullable','integer','min:0'],
            'usage_limit' => ['nullable','integer','min:1'],
            'per_user_limit' => ['nullable','integer','min:1'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['min_subtotal'] = (int)($data['min_subtotal'] ?? 0);
        $data['per_user_limit'] = (int)($data['per_user_limit'] ?? 1);
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        if ($data['type'] === 'percent') {
            $data['value'] = min(100, max(1, (int)$data['value']));
        }

        Voucher::create($data);
        return redirect()->route('admin.vouchers.index')->with('success','Voucher dibuat.');
    }

    public function edit(Voucher $voucher)
    {
        $shops = Shop::orderBy('name')->get();
        return view('admin.vouchers.edit', compact('voucher','shops'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'shop_id' => ['nullable','integer','exists:shops,id'],
            'type' => ['required','in:fixed,percent,shipping'],
            'value' => ['required','integer','min:1'],
            'min_subtotal' => ['nullable','integer','min:0'],
            'max_discount' => ['nullable','integer','min:0'],
            'usage_limit' => ['nullable','integer','min:1'],
            'per_user_limit' => ['nullable','integer','min:1'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['min_subtotal'] = (int)($data['min_subtotal'] ?? 0);
        $data['per_user_limit'] = (int)($data['per_user_limit'] ?? 1);
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        if ($data['type'] === 'percent') {
            $data['value'] = min(100, max(1, (int)$data['value']));
        }

        $voucher->update($data);
        return back()->with('success','Voucher diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success','Voucher dihapus.');
    }
}
