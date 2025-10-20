<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ], [
            'quantity' => 0
        ]);

        $cart->increment('quantity', $request->input('quantity', 1));

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Đã xóa khỏi giỏ hàng!');
    }
}

