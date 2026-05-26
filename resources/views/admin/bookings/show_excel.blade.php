<?php
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="Chi_tiet_don_hang_' . $booking->booking_code . '.xls"');
header('Cache-Control: max-age=0');
echo "\xEF\xBB\xBF"; // UTF-8 BOM
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .title { font-size: 16pt; font-weight: bold; text-align: center; color: #1e3a8a; }
        .section-header { font-weight: bold; background-color: #f3f4f6; color: #374151; height: 30px; }
        .label { font-weight: bold; color: #4b5563; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .highlight { font-weight: bold; color: #0284c7; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="4" class="title" style="height: 40px; vertical-align: middle;">HÓA ĐƠN CHI TIẾT ĐƠN ĐẶT TOUR</td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px;"></td>
        </tr>
        <tr>
            <td class="label">Mã Đơn Hàng:</td>
            <td class="highlight">#{{ $booking->booking_code }}</td>
            <td class="label">Ngày Đặt:</td>
            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Trạng Thái Đơn:</td>
            <td>{{ $booking->status }}</td>
            <td class="label">Trạng Thái Thanh Toán:</td>
            <td>{{ $booking->payment_status == 'Paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
        </tr>
        <tr>
            <td colspan="4" class="section-header">THÔNG TIN KHÁCH HÀNG</td>
        </tr>
        <tr>
            <td class="label">Họ và Tên:</td>
            <td>{{ $booking->user->full_name }}</td>
            <td class="label">Email:</td>
            <td>{{ $booking->user->email }}</td>
        </tr>
        <tr>
            <td class="label">Số Điện Thoại:</td>
            <td colspan="3" style="mso-number-format:'\@';">{{ $booking->user->phone ?? 'Chưa cập nhật' }}</td>
        </tr>
        <tr>
            <td colspan="4" class="section-header">THÔNG TIN TOUR DU LỊCH</td>
        </tr>
        <tr>
            <td class="label">Tên Tour:</td>
            <td colspan="3" class="highlight">{{ $booking->departure->tour->title }}</td>
        </tr>
        <tr>
            <td class="label">Ngày Khởi Hành:</td>
            <td>{{ $booking->departure->start_date }}</td>
            <td class="label">Thời Gian:</td>
            <td>{{ $booking->departure->tour->duration }}</td>
        </tr>
        <tr>
            <td class="label">Tổng Thành Tiền:</td>
            <td colspan="3" style="font-weight: bold; color: #b91c1c; font-size: 12pt;">{{ number_format($booking->total_price) }} VNĐ</td>
        </tr>
        <tr>
            <td class="label">Ghi Chú:</td>
            <td colspan="3" style="font-style: italic; color: #6b7280;">{{ $booking->notes ?? 'Không có' }}</td>
        </tr>
        <tr>
            <td colspan="4" class="section-header">DANH SÁCH HÀNH KHÁCH</td>
        </tr>
        <tr style="background-color: #f9fafb; font-weight: bold;">
            <th colspan="1">STT</th>
            <th colspan="2">Họ và Tên</th>
            <th colspan="1">Số CMND/CCCD</th>
        </tr>
        @foreach($booking->passengers as $index => $passenger)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td colspan="2">{{ $passenger->name }}</td>
            <td style="mso-number-format:'\@';">{{ $passenger->id_card ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
