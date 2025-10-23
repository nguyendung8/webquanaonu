<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ff6b6b;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        
        .content {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .customer-info, .order-info {
            width: 48%;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #ff6b6b;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .info-row {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #ff6b6b;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        
        .total-row {
            margin-bottom: 5px;
        }
        
        .total-label {
            font-weight: bold;
            width: 150px;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #ff6b6b;
            border-top: 2px solid #ff6b6b;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        .payment-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .payment-method {
            font-weight: bold;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">👕 Pamela Shop</div>
        <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
        <div class="invoice-number">Đơn hàng #{{ $order->id }}</div>
    </div>

    <div class="content">
        <div class="customer-info">
            <div class="section-title">THÔNG TIN KHÁCH HÀNG</div>
            <div class="info-row">
                <span class="info-label">Tên:</span>
                {{ $order->user->username }}
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                {{ $order->user->email }}
            </div>
            @if($order->user->phone)
            <div class="info-row">
                <span class="info-label">SĐT:</span>
                {{ $order->user->phone }}
            </div>
            @endif
            @if($order->user->address)
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                {{ $order->user->address }}
            </div>
            @endif
        </div>

        <div class="order-info">
            <div class="section-title">THÔNG TIN ĐƠN HÀNG</div>
            <div class="info-row">
                <span class="info-label">Ngày đặt:</span>
                {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                @if($order->status === 'pending')
                    <span class="status-badge status-pending">Chờ xử lý</span>
                @elseif($order->status === 'completed')
                    <span class="status-badge status-completed">Hoàn thành</span>
                @else
                    <span class="status-badge status-cancelled">Đã hủy</span>
                @endif
            </div>
            <div class="info-row">
                <span class="info-label">Thanh toán:</span>
                <span class="payment-method">
                    @if($order->payment_method === 'TRANSFER')
                        Chuyển khoản
                    @else
                        COD (Thanh toán khi nhận hàng)
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="section-title">CHI TIẾT SẢN PHẨM</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%">STT</th>
                <th style="width: 40%">Sản phẩm</th>
                <th style="width: 15%">Size/Màu</th>
                <th style="width: 15%">Đơn giá</th>
                <th style="width: 10%">Số lượng</th>
                <th style="width: 15%">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</td>
                <td>
                    @if($item->size || $item->color)
                        @if($item->size)
                            Size: {{ $item->size }}
                        @endif
                        @if($item->size && $item->color)
                            <br>
                        @endif
                        @if($item->color)
                            Màu: {{ $item->color }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row" style="display:flex;align-items:center;gap:10px">
            <span class="total-label">Tạm tính:</span>
            <span>{{ number_format($order->total, 0, ',', '.') }}đ</span>
        </div>
        <div class="total-row" style="display:flex;align-items:center;gap:10px">
            <span class="total-label">Phí vận chuyển:</span>
            <span>Miễn phí</span>
        </div>
        <div class="total-row grand-total" style="display:flex;align-items:center;gap:10px">
            <span class="total-label">TỔNG CỘNG:</span>
            <span>{{ number_format($order->total, 0, ',', '.') }}đ</span>
        </div>
    </div>

    @if($order->payment_method === 'TRANSFER' && $order->payment_img)
    <div class="payment-info">
        <div class="section-title">THÔNG TIN THANH TOÁN</div>
        <div class="info-row">
            <span class="info-label">Phương thức:</span>
            Chuyển khoản ngân hàng
        </div>
        <div class="info-row">
            <span class="info-label">Ảnh xác nhận:</span>
            Đã tải lên
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Pamela Shop - Cửa hàng thời trang nữ cao cấp</strong></p>
        <p>Email: contact@pamelashop.com | Phone: 0123 456 789</p>
        <p>Cảm ơn bạn đã tin tưởng và mua sắm tại Pamela Shop!</p>
        <p>Xuất hóa đơn ngày: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
