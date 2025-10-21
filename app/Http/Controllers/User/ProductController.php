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

        // Cats section with real-time availability check
        $catQuery = Cat::query();

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

        // Check real-time availability for each cat
        $cats->transform(function ($cat) {
            $now = now();
            $today = $now->format('Y-m-d');
            $currentTime = $now->format('H:i:s');

            // Check if cat is currently booked (only confirmed bookings)
            $isCurrentlyBooked = \App\Models\Booking::where('cat_id', $cat->id)
                ->where('booking_date', $today)
                ->where('status', 'confirmed')
                ->where(function($query) use ($currentTime) {
                    $query->where('booking_time', '<=', $currentTime)
                          ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) > ?', [$currentTime]);
                })
                ->exists();

            // Check if cat has any future confirmed bookings
            $hasFutureBookings = \App\Models\Booking::where('cat_id', $cat->id)
                ->where('status', 'confirmed')
                ->where(function($query) use ($today, $currentTime) {
                    $query->where('booking_date', '>', $today)
                          ->orWhere(function($subQuery) use ($today, $currentTime) {
                              $subQuery->where('booking_date', $today)
                                       ->where('booking_time', '>', $currentTime);
                          });
                })
                ->exists();

            $cat->is_available = !$isCurrentlyBooked && !$hasFutureBookings;
            return $cat;
        });

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

