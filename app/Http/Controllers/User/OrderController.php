<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    public function exportPdf(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('user', 'items.product');
        
        $pdf = Pdf::loadView('pdf.order', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("don-hang-{$order->id}.pdf");
    }
}

