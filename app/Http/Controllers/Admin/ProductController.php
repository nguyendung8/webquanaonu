<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return redirect()->route('admin.products.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'category_id' => ['required','exists:category,id'],
            'price' => ['required','numeric','min:0'],
            'description' => ['nullable','string'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $data['image'] = 'storage/products/' . $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm thành công.');
    }


    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'category_id' => ['required','exists:category,id'],
            'price' => ['required','numeric','min:0'],
            'description' => ['nullable','string'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $data['image'] = 'storage/products/' . $filename;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    public function toggleAvailability(Request $request, Product $product)
    {
        $product->update(['availability' => $request->availability]);

        return response()->json(['success' => true]);
    }
}

