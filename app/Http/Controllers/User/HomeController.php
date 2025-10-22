<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $latestProducts = Product::with(['category', 'sizes', 'colors', 'reviews'])
            ->where('availability', true)
            ->latest()
            ->take(10)
            ->get();

        return view('user.home', compact('latestProducts'));
    }
}

