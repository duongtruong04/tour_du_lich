@extends('layouts.admin')

@section('title', 'Cấu hình Hệ thống')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Cấu hình <span class="text-teal-600">Hệ thống</span></h1>
            <p class="text-slate-500 mt-2 font-medium">Quản lý thông tin chung hiển thị trên website</p>
        </div>
        <div class="p-4 bg-teal-50 text-teal-600 rounded-2xl">
            <i class="fas fa-cog text-2xl animate-spin-slow"></i>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl font-bold flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Hero Section Config -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-image"></i>
                </span>
                Giao diện Trang chủ (Hero Section)
            </h2>

            <div class="space-y-6">
                @if(isset($settings['hero_image']))
                    <div class="relative w-full h-48 rounded-2xl overflow-hidden border border-slate-100">
                        <img src="{{ asset('storage/' . $settings['hero_image']) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-dark/40 backdrop-blur-sm py-2 px-4">
                            <p class="text-[9px] font-black text-white uppercase tracking-widest text-center">Ảnh Banner Hiện Tại</p>
                        </div>
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Tải lên Banner mới</label>
                    <input type="file" name="hero_image" accept="image/*" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-bold text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    <p class="mt-1 text-[10px] text-slate-400 font-bold italic ml-2">Kích thước khuyên dùng: 1920x1080px (Tỷ lệ 16:9)</p>
                </div>
            </div>
        </div>
        
        <!-- General Info Section -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle"></i>
                </span>
                Thông tin thương hiệu
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Tên Website</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Slogan / Tiêu đề website</label>
                    <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Logo Text</label>
                    <input type="text" name="logo_text" value="{{ $settings['logo_text'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-phone"></i>
                </span>
                Thông tin liên hệ
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Email liên hệ</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Số điện thoại / Hotline</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Địa chỉ trụ sở</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
            </div>
        </div>

        <!-- Bank Account Section -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-university"></i>
                </span>
                Thông tin thanh toán chuyển khoản
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Ngân hàng</label>
                    <select name="bank_name" class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 appearance-none">
                        <option value="">Chọn ngân hàng</option>
                        @php
                            $banks = [
                                'Vietcombank' => 'Vietcombank (Ngân hàng Ngoại thương Việt Nam)',
                                'VietinBank' => 'VietinBank (Ngân hàng Công Thương Việt Nam)',
                                'BIDV' => 'BIDV (Ngân hàng Đầu tư và Phát triển Việt Nam)',
                                'Agribank' => 'Agribank (Ngân hàng Nông nghiệp và PTNT Việt Nam)',
                                'Techcombank' => 'Techcombank (Ngân hàng Kỹ Thương Việt Nam)',
                                'MBBank' => 'MBBank (Ngân hàng Quân Đội)',
                                'ACB' => 'ACB (Ngân hàng Á Châu)',
                                'VPBank' => 'VPBank (Ngân hàng Việt Nam Thịnh Vượng)',
                                'Sacombank' => 'Sacombank (Ngân hàng Sài Gòn Thương Tín)',
                                'TPBank' => 'TPBank (Ngân hàng Tiên Phong)',
                                'VIB' => 'VIB (Ngân hàng Quốc Tế)',
                                'HDBank' => 'HDBank (Ngân hàng Phát triển TP HCM)',
                                'SHB' => 'SHB (Ngân hàng Sài Gòn - Hà Nội)'
                            ];
                            $currentBank = $settings['bank_name'] ?? '';
                        @endphp
                        @foreach($banks as $key => $name)
                            <option value="{{ $key }}" {{ $currentBank == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Số tài khoản</label>
                    <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700" placeholder="Ví dụ: 1903...">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Tên chủ tài khoản</label>
                    <input type="text" name="bank_account_name" value="{{ $settings['bank_account_name'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700 uppercase" placeholder="Ví dụ: NGUYEN VAN A">
                </div>
            </div>
        </div>

        <!-- AI Chatbot Section -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-robot"></i>
                </span>
                Cấu hình AI Chatbot (OpenRouter)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">OpenRouter API Key</label>
                    <input type="text" name="openrouter_api_key" value="{{ $settings['openrouter_api_key'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700" placeholder="Nhập OpenRouter API Key">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">OpenRouter Model</label>
                    <input type="text" name="openrouter_model" value="{{ $settings['openrouter_model'] ?? 'openai/gpt-oss-120b:free' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700" placeholder="Ví dụ: openai/gpt-oss-120b:free">
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-8 flex items-center">
                <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-share-alt"></i>
                </span>
                Mạng xã hội
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Facebook URL</label>
                    <input type="text" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 ml-1">Instagram URL</label>
                    <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-teal-500 transition-all font-medium text-slate-700">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-10 py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg shadow-teal-100 transition-all transform hover:-translate-y-1 active:scale-95">
                Lưu tất cả thay đổi
            </button>
        </div>
    </form>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
@endsection
