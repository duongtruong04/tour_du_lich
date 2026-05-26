<?php
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="Danh_sach_don_hang.xls"');
header('Cache-Control: max-age=0');
echo "\xEF\xBB\xBF"; // UTF-8 BOM
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .title { font-size: 14pt; font-weight: bold; text-align: center; color: #1e3a8a; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; border: 1px solid #d1d5db; padding: 10px; }
        td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="11" class="title" style="height: 40px; vertical-align: middle;">DANH SÁCH ĐƠN ĐẶT TOUR</td>
        </tr>
        <tr>
            <td colspan="11" style="height: 10px;"></td>
        </tr>
        <thead>
            <tr>
                <th>Mã Đơn</th>
                <th>Tên Khách Hàng</th>
                <th>Email Khách Hàng</th>
                <th>Số Điện Thoại</th>
                <th>Tên Tour</th>
                <th>Ngày Khởi Hành</th>
                <th>Số Khách</th>
                <th>Tổng Tiền (VNĐ)</th>
                <th>Trạng Thái Đơn</th>
                <th>Thanh Toán</th>
                <th>Ghi Chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td class="font-bold">#{{ $booking->booking_code }}</td>
                <td>{{ $booking->user->full_name }}</td>
                <td>{{ $booking->user->email }}</td>
                <td style="mso-number-format:'\@';">{{ $booking->user->phone ?? 'Chưa cập nhật' }}</td>
                <td>{{ $booking->departure->tour->title ?? 'N/A' }}</td>
                <td>{{ $booking->departure->start_date }}</td>
                <td class="text-center">{{ count($booking->passengers) }}</td>
                <td class="text-right font-bold">{{ number_format($booking->total_price) }}</td>
                <td>{{ $booking->status }}</td>
                <td>{{ $booking->payment_status == 'Paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                <td>{{ $booking->notes ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
