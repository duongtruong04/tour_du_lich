<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In báo cáo - {{ $settings['site_name'] ?? 'Tour Du Lịch' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0 !important; margin: 0 !important; }
            .print-container { width: 100% !important; max-width: none !important; border: none !important; shadow: none !important; padding: 20px !important; }
        }
        body { background-color: #f3f7f7; font-family: 'Be Vietnam Pro', sans-serif; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-4xl mx-auto bg-white p-12 shadow-sm border border-teal-100 rounded-3xl print-container">
        <!-- Branded Header -->
        <div class="flex justify-between items-start border-b-2 border-teal-50 pb-10 mb-10">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-paper-plane text-white"></i>
                    </div>
                    <h1 class="text-2xl font-black text-teal-950 tracking-tighter uppercase">{{ $settings['logo_text'] ?? 'TourTravel' }}</h1>
                </div>
                <p class="text-teal-600 text-xs font-black uppercase tracking-widest mt-1">{{ $settings['site_title'] ?? 'Trải nghiệm du lịch chuyên nghiệp' }}</p>
                <div class="text-slate-400 text-[10px] font-bold mt-6 leading-relaxed uppercase tracking-wider">
                    <p class="flex items-center"><i class="fas fa-map-marker-alt w-4"></i> {{ $settings['address'] ?? 'N/A' }}</p>
                    <p class="flex items-center mt-1"><i class="fas fa-phone-alt w-4"></i> {{ $settings['contact_phone'] ?? 'N/A' }} | {{ $settings['contact_email'] ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-teal-600 font-black text-2xl uppercase tracking-tighter">@yield('invoice_type', 'BÁO CÁO HỆ THỐNG')</div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-2 italic">Ngày xuất: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @yield('content')

        <!-- Footer -->
        <div class="mt-20 pt-10 border-t border-teal-50 text-center text-slate-400 text-[10px] uppercase font-black tracking-widest leading-relaxed">
            <p>Đây là tài liệu được xuất trực tiếp từ hệ thống quản trị {{ $settings['site_name'] ?? 'Tour Travel' }}.</p>
            <p class="mt-2 text-teal-600 opacity-50 italic lowercase">© {{ date('Y') }} {{ $settings['site_name'] ?? 'Tour Travel' }}. all rights reserved.</p>
        </div>
    </div>

    <div class="fixed bottom-10 right-10 no-print">
        <button onclick="window.print()" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-2xl shadow-teal-500/30 flex items-center transition-all transform hover:-translate-y-2 active:scale-95 scale-110">
             <i class="fas fa-print mr-3"></i>
             IN / XUẤT PDF
        </button>
    </div>
</body>
</html>
