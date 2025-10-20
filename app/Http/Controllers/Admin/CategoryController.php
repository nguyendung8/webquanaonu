<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:50','unique:category,name'],
            'description' => ['nullable','string','max:255'],
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã thêm danh mục thành công.');
    }


    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:50','unique:category,name,'.$category->id],
            'description' => ['nullable','string','max:255'],
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục.');
    }
}

