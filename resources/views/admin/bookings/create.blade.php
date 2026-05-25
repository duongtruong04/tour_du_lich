@extends('layouts.admin')

@section('title', 'Đặt Tour Tại Quầy')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf
        <!-- Thông tin khách hàng -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-circle mr-2 text-indigo-500"></i> Thông tin khách hàng
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required placeholder="customer@example.com" 
                        class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1 italic">Hệ thống sẽ tự động tạo tài khoản nếu email mới.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                    <input type="text" name="full_name" required placeholder="Nguyễn Văn A" 
                        class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Chọn Tour & Ngày khởi hành -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map mr-2 text-indigo-500"></i> Chọn Tour & Ngày khởi hành
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chọn Tour</label>
                    <select id="tour_select" class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" onchange="updateDepartures()">
                        <option value="">-- Chọn Tour --</option>
                        @foreach($tours as $tour)
                        <option value="{{ $tour->id }}" data-departures='{{ json_encode($tour->departures) }}'>{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày khởi hành</label>
                    <select name="departure_id" id="departure_select" required class="w-full border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">-- Chọn ngày khởi hành --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Danh sách hành khách -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center justify-between">
                <span><i class="fas fa-users mr-2 text-indigo-500"></i> Danh sách hành khách</span>
                <button type="button" onclick="addPassengerRow()" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full hover:bg-indigo-100 transition">
                    + Thêm hành khách
                </button>
            </h3>
            <div id="passenger-container" class="space-y-3">
                <div class="passenger-row flex flex-col md:flex-row gap-3 p-3 bg-gray-50 rounded-lg relative">
                    <div class="flex-1">
                        <input type="text" name="passengers[0][name]" required placeholder="Tên hành khách" class="w-full border p-2 rounded-lg text-sm">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="passengers[0][id_card]" placeholder="CMND/CCCD/Passport" class="w-full border p-2 rounded-lg text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100 transition">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-md shadow-indigo-200">
                Xác nhận Đặt Tour
            </button>
        </div>
    </form>
</div>

<script>
    function updateDepartures() {
        const tourSelect = document.getElementById('tour_select');
        const departureSelect = document.getElementById('departure_select');
        const selectedOption = tourSelect.options[tourSelect.selectedIndex];
        
        departureSelect.innerHTML = '<option value="">-- Chọn ngày khởi hành --</option>';
        
        if (selectedOption.value) {
            const departures = JSON.parse(selectedOption.getAttribute('data-departures'));
            departures.forEach(dep => {
                const opt = document.createElement('option');
                opt.value = dep.id;
                opt.textContent = `${dep.start_date} (Còn ${dep.available_seats} chỗ)`;
                departureSelect.appendChild(opt);
            });
        }
    }

    let passengerIndex = 1;
    function addPassengerRow() {
        const container = document.getElementById('passenger-container');
        const row = document.createElement('div');
        row.className = 'passenger-row flex flex-col md:flex-row gap-3 p-3 bg-gray-50 rounded-lg relative';
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="passengers[${passengerIndex}][name]" required placeholder="Tên hành khách" class="w-full border p-2 rounded-lg text-sm">
            </div>
            <div class="flex-1">
                <input type="text" name="passengers[${passengerIndex}][id_card]" placeholder="CMND/CCCD/Passport" class="w-full border p-2 rounded-lg text-sm">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-white text-red-500 w-5 h-5 rounded-full shadow border hover:bg-red-50 transition flex items-center justify-center">
                <i class="fas fa-times text-[10px]"></i>
            </button>
        `;
        container.appendChild(row);
        passengerIndex++;
    }
</script>
@endsection
