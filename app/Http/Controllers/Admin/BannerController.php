<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->latest('id')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable','string','max:150'],
            'link_url' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
            'image' => ['required','image','max:4096'],
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $data['title'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => (bool)($request->boolean('is_active', true)),
            'image_path' => $path,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner dibuat.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => ['nullable','string','max:150'],
            'link_url' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
            'image' => ['nullable','image','max:4096'],
        ]);

        if ($request->hasFile('image')) {
            // optional: keep old file
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->title = $data['title'] ?? null;
        $banner->link_url = $data['link_url'] ?? null;
        $banner->sort_order = (int)($data['sort_order'] ?? 0);
        $banner->starts_at = $data['starts_at'] ?? null;
        $banner->ends_at = $data['ends_at'] ?? null;
        $banner->is_active = (bool)($request->boolean('is_active', false));
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner dihapus.');
    }
}
