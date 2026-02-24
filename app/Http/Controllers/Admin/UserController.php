<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->query('q',''));

        $users = User::query()
            ->when($q !== '', fn($qr) => $qr->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%"))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users','q'));
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error','Tidak bisa menonaktifkan diri sendiri.');
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success','Status user diperbarui.');
    }

    public function setRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required','in:customer,seller,admin'],
            'admin_role' => ['nullable','in:cs,finance,moderator,super'],
        ]);
        $payload = ['role' => $request->role];
        $payload['admin_role'] = $request->role === 'admin' ? ($request->input('admin_role') ?: 'super') : null;
        $user->update($payload);
        return back()->with('success','Role user diperbarui.');
    }
}
