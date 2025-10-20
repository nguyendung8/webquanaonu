<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Cat;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $cats = Cat::orderBy('name')->get();

        return view('user.feedback', compact('products', 'cats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'cat_id' => ['nullable', 'exists:cats,id'],
            'content' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $data['user_id'] = auth()->id();

        Feedback::create($data);

        return redirect()->route('user.feedback')->with('success', 'Cảm ơn bạn đã gửi phản hồi!');
    }
}

