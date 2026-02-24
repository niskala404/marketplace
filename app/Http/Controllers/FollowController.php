<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, Shop $shop)
    {
        $user = $request->user();
        if (!$shop->is_active) abort(404);

        $exists = $shop->followers()->where('users.id', $user->id)->exists();
        if ($exists) {
            $shop->followers()->detach($user->id);
            return back()->with('success', 'Berhenti mengikuti toko.');
        }

        $shop->followers()->attach($user->id);
        return back()->with('success', 'Berhasil mengikuti toko.');
    }

    public function index(Request $request)
    {
        $shops = $request->user()->followedShops()->where('is_active', true)->latest()->paginate(12);
        return view('account.following', compact('shops'));
    }
}
