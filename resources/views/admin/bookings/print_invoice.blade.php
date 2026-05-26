<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $booking->booking_code }} - TOURTRAVEL</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f1f5f9;
        }
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="py-10 px-4">

    <!-- Floating Top Control Bar (no-print) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs uppercase tracking-wider transition flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Quay lại chi tiết đơn
            </a>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl font-black text-xs uppercase tracking-widest transition shadow-lg shadow-emerald-500/20 flex items-center gap-1.5">
                <i class="fas fa-print"></i> In hóa đơn
            </button>
        </div>
    </div>

    <!-- Main Printable Invoice Container -->
    <div class="max-w-4xl mx-auto bg-white p-12 rounded-3xl shadow-xl border border-slate-100 print-container">
        
        <!-- Header: Logo & Invoice Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-8 mb-8 border-slate-100">
            <div class="flex items-center gap-3 mb-6 sm:mb-0">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-paper-plane text-white text-lg"></i>
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tighter uppercase text-slate-800">TOURTRAVEL</span>
                    <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest leading-none mt-1">Trải nghiệm vượt trội</p>
                </div>
            </div>
            <div class="text-left sm:text-right">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Hóa đơn thanh toán</h1>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mt-1">Mã hóa đơn: <span class="text-slate-800 font-bold">#{{ $booking->booking_code }}</span></p>
                <p class="text-xs text-slate-400 font-medium mt-1">Ngày xuất: <span class="text-slate-600 font-semibold">{{ now()->format('d/m/Y H:i') }}</span></p>
            </div>
        </div>

        <!-- Details Grid: Buyer vs Seller -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Buyer Info -->
            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100/50">
                <h3 class="text-xs font-black uppercase tracking-wider text-emerald-600 mb-3">Đơn vị mua hàng / Customer</h3>
                <div class="space-y-1.5 text-xs text-slate-600 font-medium">
                    <p class="text-sm font-bold text-slate-800">{{ $booking->user->full_name }}</p>
                    <p><i class="far fa-envelope mr-1.5 text-slate-400"></i> {{ $booking->user->email }}</p>
                    <p><i class="fas fa-phone-alt mr-1.5 text-slate-400"></i> {{ $booking->user->phone ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
            
            <!-- Seller Info -->
            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100/50">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">Đơn vị cung cấp / Provider</h3>
                <div class="space-y-1.5 text-xs text-slate-600 font-medium">
                    <p class="text-sm font-bold text-slate-800">CÔNG TY CỔ PHẦN DU LỊCH TOURTRAVEL</p>
                    <p><i class="fas fa-map-marker-alt mr-1.5 text-slate-400"></i> 123 Nguyễn Trãi, Thanh Xuân, Hà Nội</p>
                    <p><i class="fas fa-phone-alt mr-1.5 text-slate-400"></i> Hotline: 1900 6868 | contact@tourtravel.com</p>
                </div>
            </div>
        </div>

        <!-- Table: Tour Service details -->
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-3">Chi tiết dịch vụ / Service details</h3>
            <div class="overflow-hidden border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase">
                            <th class="p-4 w-12 text-center">STT</th>
                            <th class="p-4">Tên dịch vụ du lịch (Tour)</th>
                            <th class="p-4 text-center">Ngày đi</th>
                            <th class="p-4 text-center">Số lượng</th>
                            <th class="p-4 text-right">Đơn giá</th>
                            <th class="p-4 text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <tr>
                            <td class="p-4 text-center">1</td>
                            <td class="p-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $booking->departure->tour->title }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Mã Tour: {{ $booking->departure->tour->tour_code ?? 'DEP-' . $booking->departure->tour->id }}</div>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($booking->departure->start_date)->format('d/m/Y') }}
                            </td>
                            <td class="p-4 text-center">{{ count($booking->passengers) }}</td>
                            <td class="p-4 text-right whitespace-nowrap">
                                {{ number_format($booking->departure->price_override ?? $booking->departure->tour->base_price) }}đ
                            </td>
                            <td class="p-4 text-right font-bold text-slate-800 whitespace-nowrap">
                                {{ number_format($booking->total_price) }}đ
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals Block -->
        <div class="flex justify-end mb-8">
            <div class="w-full sm:w-80 space-y-2.5 text-xs font-semibold text-slate-600">
                <div class="flex justify-between">
                    <span>Cộng tiền dịch vụ:</span>
                    <span class="text-slate-800 font-bold">{{ number_format($booking->total_price) }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span>Thuế GTGT (VAT 0%):</span>
                    <span class="text-slate-800">0đ</span>
                </div>
                <div class="flex justify-between items-center border-t pt-3 border-slate-100">
                    <span class="text-sm font-bold text-slate-800">Tổng cộng thanh toán:</span>
                    <span class="text-lg font-black text-emerald-600">{{ number_format($booking->total_price) }}đ</span>
                </div>
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span>Trạng thái:</span>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        @if($booking->payment_status == 'Paid')
                            bg-emerald-100 text-emerald-800
                        @else
                            bg-amber-100 text-amber-800
                        @endif">
                        {{ $booking->payment_status == 'Paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Passenger List Details -->
        @if($booking->passengers->count() > 0)
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-3">Danh sách khách đi tour / Passenger List</h3>
            <div class="overflow-hidden border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase">
                            <th class="p-3 w-12 text-center">STT</th>
                            <th class="p-3">Họ và tên</th>
                            <th class="p-3">CMND / CCCD / Passport</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($booking->passengers as $idx => $passenger)
                        <tr>
                            <td class="p-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $passenger->name }}</td>
                            <td class="p-3 font-semibold text-slate-500">{{ $passenger->id_card ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Customer Note -->
        @if($booking->notes)
        <div class="mb-8 p-4 bg-amber-50/50 border border-amber-100/50 rounded-2xl text-xs font-medium text-slate-600">
            <span class="font-bold text-amber-800 uppercase block mb-1">Ghi chú đơn hàng:</span>
            {{ $booking->notes }}
        </div>
        @endif

        <!-- Signatures Row -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-100 text-center text-xs">
            <div>
                <p class="font-bold text-slate-800 uppercase">Khách hàng / Customer</p>
                <p class="text-[10px] text-slate-400 mt-0.5">(Ký, ghi rõ họ tên)</p>
                <div class="h-20"></div>
                <p class="font-bold text-slate-800">{{ $booking->user->full_name }}</p>
            </div>
            <div>
                <p class="font-bold text-slate-800 uppercase">Người lập phiếu / Cashier</p>
                <p class="text-[10px] text-slate-400 mt-0.5">(Ký, ghi rõ họ tên)</p>
                <div class="h-20"></div>
                <p class="font-bold text-slate-800">{{ Auth::user()->full_name }}</p>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-slate-400 text-[10px] font-medium mt-12 border-t pt-6 border-slate-100">
            <p>Cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của TourTravel!</p>
            <p class="mt-1">Hotline: 1900 6868 | Email: contact@tourtravel.com | Website: www.tourtravel.com</p>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>

</body>
</html>
