@extends('layouts.admin')

@section('title', 'Tổng quan Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    <!-- Stats Cards -->
    <div class="card relative overflow-hidden group">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-primary">
                <i class="fas fa-route text-xl"></i>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tours</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['total_tours']) }}</h3>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Sản phẩm đang hoạt động</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-teal-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    </div>

    <div class="card relative overflow-hidden group border-b-4 border-b-secondary">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-secondary">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bookings</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['total_bookings']) }}</h3>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Đơn đặt tour mới</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-orange-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    </div>

    <div class="card relative overflow-hidden group">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <i class="fas fa-coins text-xl"></i>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Revenue</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['total_revenue']) }}đ</h3>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Doanh thu đã thanh toán</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    </div>

    <div class="card relative overflow-hidden group">
        <div class="flex items-center justify-between mb-4 relative z-10">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                <i class="fas fa-users text-xl"></i>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Clients</span>
        </div>
        <div class="relative z-10">
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['total_customers']) }}</h3>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Khách hàng thành viên</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Sales Chart -->
    <div class="lg:col-span-2 card p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Biểu đồ doanh thu</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Doanh số bán tour theo tháng (VNĐ)</p>
            </div>
            <select class="bg-slate-50 border-none rounded-xl px-4 py-2 text-xs font-bold text-slate-600 focus:ring-2 focus:ring-primary transition-all">
                <option>Năm {{ date('Y') }}</option>
            </select>
        </div>
        <div class="h-[350px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Tours -->
    <div class="card p-8 bg-slate-900 text-white">
        <h4 class="text-lg font-black tracking-tighter uppercase mb-8">Tour được đặt <span class="text-primary italic">nhiều nhất</span></h4>
        <div class="space-y-6">
            @foreach($top_tours as $tour)
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 flex-shrink-0">
                    @if($tour->images->first())
                        <img src="{{ $tour->images->first()->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="text-xs font-black uppercase tracking-tight truncate group-hover:text-primary transition-colors">{{ $tour->title }}</h5>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $tour->bookings_count }} lượt đặt</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-black text-primary">{{ number_format($tour->base_price) }}đ</p>
                </div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-primary w-full mt-10 !text-[10px]">Xem báo cáo chi tiết</a>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h4 class="text-lg font-black text-slate-800 tracking-tighter uppercase">Đơn đặt tour mới nhất</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Cập nhật theo thời gian thực</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline">Xem tất cả <i class="fas fa-arrow-right ml-2"></i></a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50">
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Mã đơn</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Khách hàng</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tour</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ngày khởi hành</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Tổng tiền</th>
                    <th class="p-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($recent_bookings as $booking)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-5">
                        <span class="text-xs font-black text-slate-800">#{{ $booking->booking_code }}</span>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->user->full_name) }}&background=E2E8F0&color=64748B" class="w-8 h-8 rounded-lg">
                            <div>
                                <p class="text-[11px] font-black text-slate-800 leading-none">{{ $booking->user->full_name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $booking->user->phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="text-[11px] font-black text-slate-700 line-clamp-1 uppercase tracking-tighter">{{ $booking->departure->tour->title }}</p>
                    </td>
                    <td class="p-5">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ date('d/m/Y', strtotime($booking->departure->start_date)) }}</p>
                    </td>
                    <td class="p-5 text-right">
                        <span class="text-xs font-black text-emerald-600">{{ number_format($booking->total_price) }}đ</span>
                    </td>
                    <td class="p-5 text-center">
                        @php
                            $status_colors = [
                                'Pending' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'Confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                'Completed' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                            ];
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border text-[9px] font-black uppercase tracking-widest {{ $status_colors[$booking->status] ?? 'bg-slate-100' }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenue_by_month);
    
    // Fill in missing months
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dataValues = Array(12).fill(0);
    revenueData.forEach(item => {
        dataValues[item.month - 1] = item.total;
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: dataValues,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: {
                        font: { size: 10, weight: '700' },
                        color: '#94a3b8',
                        callback: function(value) { return value.toLocaleString() + 'đ'; }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '700' }, color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection
