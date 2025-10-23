<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function show()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Giỏ hàng trống.');
        }

        $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);

        return view('user.checkout', compact('cartItems', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:COD,TRANSFER',
            'payment_img' => 'required_if:payment_method,TRANSFER|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'payment_img.required_if' => 'Vui lòng tải ảnh xác nhận chuyển khoản khi chọn phương thức chuyển khoản.',
            'payment_img.image' => 'File phải là hình ảnh hợp lệ.',
            'payment_img.mimes' => 'Chỉ chấp nhận jpg, jpeg, png, webp.',
            'payment_img.max' => 'Kích thước ảnh tối đa 4MB.',
        ]);

        if (empty(auth()->user()->address)) {
            return redirect()->route('user.checkout')->with('error', 'Vui lòng cập nhật địa chỉ giao hàng trước khi đặt hàng.');
        }

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Giỏ hàng trống.');
        }

        $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);

        $paymentImagePath = null;
        if ($validated['payment_method'] === 'TRANSFER' && $request->hasFile('payment_img')) {
            // store to public/storage/orders
            $file = $request->file('payment_img');
            $filename = uniqid('pay_') . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('storage/orders');
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $paymentImagePath = 'storage/orders/' . $filename; // use asset() to display
        }

        DB::transaction(function () use ($cartItems, $total, $validated, $paymentImagePath) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_img' => $paymentImagePath,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'size' => $item->size,
                    'color' => $item->color,
                ]);
            }

            Cart::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('user.orders')->with('success', 'Đặt hàng thành công!');
    }
}


