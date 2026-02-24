@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Users</h1>

    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $q }}" class="rounded-xl border-slate-200" placeholder="Cari name/email">
        <button class="px-4 py-2 rounded-xl bg-slate-900 text-white">Cari</button>
    </form>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @foreach($users as $u)
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold">{{ $u->name }}</div>
                    <div class="text-sm text-slate-500">{{ $u->email }} • role: <span class="font-semibold">{{ $u->role }}</span></div>
                    <div class="text-sm">Status: {!! $u->is_active ? '<span class="text-emerald-600 font-semibold">aktif</span>' : '<span class="text-rose-600 font-semibold">nonaktif</span>' !!}</div>
                </div>

                <div class="flex flex-col gap-2 w-64">
                    <form method="POST" action="{{ route('admin.users.role',$u) }}" class="flex flex-col gap-2">
                        @csrf
                        <div class="flex gap-2">
                          <select name="role" class="flex-1 rounded-xl border-slate-200">
                            @foreach(['customer','seller','admin'] as $r)
                                <option value="{{ $r }}" @selected($u->role===$r)>{{ $r }}</option>
                            @endforeach
                          </select>
                          <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Set</button>
                        </div>

                        <select name="admin_role" class="rounded-xl border-slate-200" title="Admin role">
                          <option value="">(admin role)</option>
                          @foreach(['cs','finance','moderator','super'] as $ar)
                            <option value="{{ $ar }}" @selected(($u->admin_role ?? '')===$ar)>{{ $ar }}</option>
                          @endforeach
                        </select>
                    </form>

                    <form method="POST" action="{{ route('admin.users.toggle',$u) }}">
                        @csrf
                        <button class="w-full px-3 py-2 rounded-xl {{ $u->is_active ? 'bg-rose-600' : 'bg-emerald-600' }} text-white">
                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
