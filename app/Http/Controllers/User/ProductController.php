<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sizes', 'colors', 'reviews'])->where('availability', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('size')) {
            $query->whereHas('sizes', function($q) use ($request) {
                $q->where('size', $request->size);
            });
        }

        if ($request->filled('color')) {
            $query->whereHas('colors', function($q) use ($request) {
                $q->where('color', 'like', '%'.$request->color.'%');
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('user.products', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'sizes', 'colors', 'images', 'reviews.user']);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('availability', true)
            ->with(['sizes', 'colors', 'reviews'])
            ->take(4)
            ->get();

        return view('user.product-detail', compact('product', 'relatedProducts'));
    }
}

