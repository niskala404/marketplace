<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'parent_id' => ['nullable','integer','exists:categories,id'],
            'image' => ['nullable','image','max:2048'],
        ]);

        $slug = Str::slug($data['name']);
        if (Category::where('slug',$slug)->exists()) $slug .= '-'.Str::random(4);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success','Kategori dibuat.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id','!=',$category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'parent_id' => ['nullable','integer','exists:categories,id'],
            'image' => ['nullable','image','max:2048'],
            'remove_image' => ['nullable','boolean'],
        ]);

        // handle remove existing image
        if ($request->boolean('remove_image') && $category->image_path) {
            Storage::disk('public')->delete($category->image_path);
            $category->image_path = null;
        }

        // handle new upload
        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $category->image_path = $request->file('image')->store('categories', 'public');
        }

        $category->name = $data['name'];
        $category->parent_id = $data['parent_id'] ?? null;
        $category->save();

        return back()->with('success','Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();
        return back()->with('success','Kategori dihapus.');
    }
}
