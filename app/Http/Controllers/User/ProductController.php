<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cat;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('availability', true);

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

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Cats section
        $catQuery = Cat::where('availability', true);

        if ($request->filled('cat_gender')) {
            $catQuery->where('gender', $request->cat_gender);
        }

        if ($request->filled('cat_age_min')) {
            $catQuery->where('age', '>=', $request->cat_age_min);
        }

        if ($request->filled('cat_age_max')) {
            $catQuery->where('age', '<=', $request->cat_age_max);
        }

        if ($request->filled('cat_price_min')) {
            $catQuery->where('price', '>=', $request->cat_price_min);
        }

        if ($request->filled('cat_price_max')) {
            $catQuery->where('price', '<=', $request->cat_price_max);
        }

        $cats = $catQuery->orderBy('created_at', 'desc')->get();

        return view('user.products', compact('products', 'categories', 'cats'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('availability', true)
            ->take(4)
            ->get();

        return view('user.product-detail', compact('product', 'relatedProducts'));
    }
}

