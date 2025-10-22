<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sizes', 'colors']);

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
            'material' => ['nullable','string','max:100'],
            'brand' => ['nullable','string','max:50'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
            'images.*' => ['nullable','image','max:2048'],
            'sizes' => ['nullable','array'],
            'sizes.*' => ['string','max:20'],
            'colors' => ['nullable','array'],
            'colors.*' => ['string','max:50'],
            'color_codes' => ['nullable','array'],
            'color_codes.*' => ['nullable','string','max:7'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $data['image'] = 'storage/products/' . $filename;
        }

        $product = Product::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/products'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'storage/products/' . $filename,
                    'is_primary' => $index === 0 ? 1 : 0
                ]);
            }
        }

        // Handle sizes
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                if (!empty($size)) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => 0
                    ]);
                }
            }
        }

        // Handle colors
        if ($request->has('colors')) {
            foreach ($request->colors as $index => $color) {
                if (!empty($color)) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'color_code' => $request->color_codes[$index] ?? null
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm thành công.');
    }


    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'category_id' => ['required','exists:category,id'],
            'price' => ['required','numeric','min:0'],
            'description' => ['nullable','string'],
            'material' => ['nullable','string','max:100'],
            'brand' => ['nullable','string','max:50'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
            'images.*' => ['nullable','image','max:2048'],
            'sizes' => ['nullable','array'],
            'sizes.*' => ['string','max:20'],
            'colors' => ['nullable','array'],
            'colors.*' => ['string','max:50'],
            'color_codes' => ['nullable','array'],
            'color_codes.*' => ['nullable','string','max:7'],
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

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/products'), $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'storage/products/' . $filename,
                    'is_primary' => 0
                ]);
            }
        }

        // Handle sizes - delete existing and create new ones
        $product->sizes()->delete();
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                if (!empty($size)) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock' => 0
                    ]);
                }
            }
        }

        // Handle colors - delete existing and create new ones
        $product->colors()->delete();
        if ($request->has('colors')) {
            foreach ($request->colors as $index => $color) {
                if (!empty($color)) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'color_code' => $request->color_codes[$index] ?? null
                    ]);
                }
            }
        }

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

