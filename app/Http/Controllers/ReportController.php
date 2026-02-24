<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:product,shop'],
            'id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:80'],
            'details' => ['nullable', 'string', 'max:2000'],
        ]);

        $type = $request->input('type');
        $id = (int) $request->input('id');

        $reportableType = $type === 'product' ? Product::class : Shop::class;
        $exists = $reportableType::query()->whereKey($id)->exists();
        abort_unless($exists, 404);

        Report::create([
            'user_id' => $request->user()?->id,
            'reportable_type' => $reportableType,
            'reportable_id' => $id,
            'reason' => $request->input('reason'),
            'details' => $request->input('details'),
            'status' => 'open',
        ]);

        return back()->with('success', 'Laporan kamu sudah dikirim. Terima kasih.');
    }
}
