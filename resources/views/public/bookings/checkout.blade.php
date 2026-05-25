@extends('layouts.app')

@section('title', 'Thanh toán & Đặt tour')

@section('content')
<!-- Progress Header -->
<header class="breadcrumb-header min-h-[45vh] flex flex-col justify-center">
    <img src="https://images.unsplash.com/photo-1454165833222-885872352391?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" class="bg-image">
    <div class="overlay opacity-60"></div>
    <div class="container relative z-10 text-center">
        <nav class="breadcrumb-nav justify-center mb-16">
            <a href="{{ route('home') }}">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <span class="text-white">Thanh toán & Đặt tour</span>
        </nav>
        <div class="max-w-xl mx-auto flex items-center justify-between mb-16 relative">
             <!-- Line -->
             <div class="absolute top-5 left-0 right-0 h-[2px] bg-white/10 -z-10"></div>
             <!-- Steps -->
             <div class="flex flex-col items-center gap-3">
                 <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-xs font-black shadow-lg shadow-emerald-500/20">
                     <i class="fas fa-check"></i>
                 </div>
                 <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Chọn Tour</span>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-xs font-black ring-8 ring-emerald-500/10 scale-110">
                     2
                 </div>
                 <span class="text-[9px] font-black text-primary uppercase tracking-widest">Thông tin</span>
             </div>
             <div class="flex flex-col items-center gap-3">
                 <div class="w-10 h-10 rounded-full bg-white/10 text-white/30 flex items-center justify-center text-xs font-black border border-white/5">
                     3
                 </div>
                 <span class="text-[9px] font-black text-white/30 uppercase tracking-widest">Hoàn tất</span>
             </div>
        </div>
        <h1 class="text-4xl md:text-7xl font-black text-white tracking-tighter uppercase mb-2 leading-none">Chi tiết <span class="text-primary italic">ĐẶT CHỖ</span></h1>
        <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.4em] opacity-80">Bước cuối cùng để bắt đầu hành trình tuyệt vời của bạn.</p>
    </div>
</header>

<!-- Checkout Main -->
<section class="py-12 md:py-16 bg-slate-50" x-data="{ 
    passengers: [{ name: '', id_card: '' }], 
    basePrice: {{ $departure->price_override ?? $departure->tour->base_price }},
    promoCode: '',
    promoType: '',
    promoValue: 0,
    promoMessage: '',
    promoApplied: false,
    agreed: false,
    promotions: {{ $promotions->map(fn($p) => ['code' => $p->code, 'type' => $p->discount_type, 'value' => $p->discount_value])->toJson() }},
    applyPromo() {
        const promo = this.promotions.find(p => p.code.toUpperCase() === this.promoCode.toUpperCase());
        if (promo) {
            this.promoType = promo.type;
            this.promoValue = promo.value;
            this.promoApplied = true;
            this.promoMessage = 'Áp dụng mã thành công!';
        } else {
            this.promoType = '';
            this.promoValue = 0;
            this.promoApplied = false;
            this.promoMessage = 'Mã không hợp lệ hoặc đã hết hạn!';
        }
    },
    getTotalPrice() {
        let total = this.passengers.length * this.basePrice;
        if (this.promoApplied) {
            if (this.promoType === 'Fixed') {
                total -= this.promoValue;
            } else if (this.promoType === 'Percentage') {
                total -= (total * this.promoValue / 100);
            }
        }
        return Math.max(0, total);
    },
    addPassenger() { this.passengers.push({ name: '', id_card: '' }) },
    removePassenger(index) { if(this.passengers.length > 1) this.passengers.splice(index, 1) }
}">
    <form action="{{ route('public.bookings.process') }}" method="POST" class="container">
        @csrf
        <input type="hidden" name="departure_id" value="{{ $departure->id }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            <div class="lg:col-span-2 space-y-8">

                <!-- THÔNG TIN HÀNH TRÌNH & THÔNG SỐ TOUR -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden">
                    <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: linear-gradient(180deg, #10b981, #0d9488);"></div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-6 flex items-center gap-3">
                        <i class="fas fa-route text-primary text-xl"></i> Thông tin chuyến đi
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-slate-600">
                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 text-center">
                            <i class="fas fa-clock text-emerald-500 text-lg mb-2 block"></i>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Thời lượng</span>
                            <strong class="text-xs font-bold text-slate-800 uppercase">{{ $tour->duration ?? $departure->tour->duration ?? '3 ngày 2 đêm' }}</strong>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 text-center">
                            <i class="fas fa-bus text-amber-500 text-lg mb-2 block"></i>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Phương tiện</span>
                            <strong class="text-xs font-bold text-slate-800 uppercase">{{ $tour->transportation ?? $departure->tour->transportation ?? 'Xe du lịch cao cấp' }}</strong>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 text-center">
                            <i class="fas fa-map-marker-alt text-blue-500 text-lg mb-2 block"></i>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Khởi hành từ</span>
                            <strong class="text-xs font-bold text-slate-800 uppercase">{{ $tour->destinations->first()->name ?? 'Toàn quốc' }}</strong>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 text-center">
                            <i class="fas fa-chair text-purple-500 text-lg mb-2 block"></i>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Chỗ trống</span>
                            <strong class="text-xs font-bold text-primary uppercase">{{ $departure->available_seats }} chỗ</strong>
                        </div>
                    </div>
                </div>

                <!-- Passenger List -->
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Thông tin hành khách</h3>
                        <button type="button" @click="addPassenger()" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-primary hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i> Thêm thành viên
                        </button>
                    </div>

                    <template x-for="(passenger, index) in passengers" :key="index">
                        <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative animate-fade-in group">
                            <div class="flex items-center justify-between mb-8">
                                <span class="px-4 py-2 bg-emerald-50 rounded-2xl text-[9px] font-black text-primary uppercase tracking-widest border border-emerald-100">
                                    Thành viên #<span x-text="index + 1"></span>
                                </span>
                                <button type="button" @click="removePassenger(index)" class="w-10 h-10 flex items-center justify-center rounded-xl text-rose-300 hover:bg-rose-50 hover:text-rose-500 transition-all opacity-0 group-hover:opacity-100" x-show="passengers.length > 1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Họ và tên <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="'passengers['+index+'][name]'" required placeholder="VÍ DỤ: NGUYỄN VĂN A" class="form-control tracking-widest uppercase font-bold">
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Số CMND/CCCD/Hộ chiếu</label>
                                    <input type="text" :name="'passengers['+index+'][id_card]'" placeholder="Nhập số giấy tờ tùy thân" class="form-control font-bold">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Payment Methods -->
                <div class="space-y-8 pt-12 border-t border-slate-200">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Thanh toán bằng</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach(['VNPay' => ['icon' => 'fa-credit-card', 'label' => 'Thanh toán VNPay'], 'Cash' => ['icon' => 'fa-university', 'label' => 'Chuyển khoản']] as $key => $method)
                        <label class="cursor-pointer group">
                            <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="hidden peer">
                            <div class="p-8 bg-white border-2 border-slate-100 rounded-[2.5rem] text-center peer-checked:border-primary peer-checked:bg-emerald-50 transition-all shadow-sm hover:shadow-xl">
                                <i class="fas {{ $method['icon'] }} text-3xl text-slate-200 group-hover:text-primary transition-colors mb-4 block"></i>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest group-hover:text-primary transition-colors">{{ $method['label'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-12 border-t border-slate-200 space-y-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ghi chú thêm (Không bắt buộc)</label>
                        <textarea name="notes" rows="4" placeholder="VD: Yêu cầu đặc biệt về chỗ ngồi, ăn uống..." class="form-control rounded-[2rem] pt-6 px-8"></textarea>
                    </div>
                    
                    <!-- CHÍNH SÁCH HOÀN HỦY & QUY ĐỊNH -->
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden">
                        <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: #f59e0b;"></div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tighter uppercase mb-6 flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle text-amber-500"></i> Chính sách hoàn hủy & Lưu ý
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-xs">
                            <div class="p-6 bg-amber-50/50 border border-amber-100 rounded-3xl text-center">
                                <span class="text-[10px] font-black text-amber-800 uppercase tracking-widest block mb-2">Hủy trước 7 ngày</span>
                                <p class="text-slate-600 font-bold m-0">Hoàn lại 100% chi phí thanh toán</p>
                            </div>

                            <div class="p-6 bg-amber-50/50 border border-amber-100 rounded-3xl text-center">
                                <span class="text-[10px] font-black text-amber-800 uppercase tracking-widest block mb-2">Hủy từ 3 - 6 ngày</span>
                                <p class="text-slate-600 font-bold m-0">Hoàn lại 50% chi phí thanh toán</p>
                            </div>

                            <div class="p-6 bg-rose-50/50 border border-rose-100 rounded-3xl text-center">
                                <span class="text-[10px] font-black text-rose-800 uppercase tracking-widest block mb-2">Hủy dưới 3 ngày</span>
                                <p class="text-slate-600 font-bold m-0">Không hoàn tiền (Phí phạt 100%)</p>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl flex items-center gap-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary text-xl flex-shrink-0">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-800 uppercase mb-1">Cần hỗ trợ thanh toán hoặc thay đổi lịch trình?</h4>
                                <p class="text-xs text-slate-500 m-0">Liên hệ ngay hotline điều hành tour <strong class="text-primary font-black">{{ $settings['contact_phone'] ?? '0123 456 789' }}</strong> để được hỗ trợ nhanh nhất 24/7.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <label class="flex items-center gap-6 cursor-pointer p-6 md:p-8 bg-white rounded-[2rem] border transition-all group"
                               :class="agreed ? 'border-primary bg-emerald-50/30' : 'border-slate-200 shadow-sm hover:border-slate-300'">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" x-model="agreed" required class="peer w-8 h-8 rounded-xl text-primary focus:ring-primary border-slate-300 transition-all cursor-pointer">
                                <div class="absolute inset-0 bg-primary/20 rounded-xl scale-150 opacity-0 peer-checked:animate-ping"></div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-black text-slate-800 uppercase tracking-tight">Xác nhận điều khoản dịch vụ</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed opacity-60">
                                    Tôi đã đọc và đồng ý với <a href="#" class="text-primary underline decoration-2 underline-offset-4">Điều khoản dịch vụ</a> và <a href="#" class="text-primary underline decoration-2 underline-offset-4">Chính sách bảo mật</a> của {{ $settings['site_name'] ?? 'Tour Travel' }}.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden text-slate-700">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[10rem] -z-10"></div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8">Tóm tắt hành trình</h4>
                        
                        <div class="flex gap-6 mb-10">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-lg shadow-teal-500/10 flex-shrink-0">
                                <img src="{{ $departure->tour->images->first() ? $departure->tour->images->first()->image_url : 'https://placehold.co/200x200' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-slate-900 uppercase tracking-tighter leading-tight line-clamp-2 mb-2">{{ $tour->title ?? $departure->tour->title }}</h5>
                                <p class="text-[9px] font-bold text-primary uppercase tracking-[0.2em] flex items-center gap-2">
                                    <i class="far fa-calendar-alt"></i> {{ date('d / m / Y', strtotime($departure->start_date)) }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5 border-t border-slate-50 pt-8 mb-8">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>Đơn giá / người</span>
                                <span class="text-slate-900">{{ number_format($departure->price_override ?? $departure->tour->base_price) }}đ</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>Số thành viên</span>
                                <span class="text-slate-900" x-text="passengers.length">1</span>
                            </div>
                            <div x-show="promoApplied" class="flex justify-between items-center text-[10px] font-bold text-primary uppercase tracking-widest">
                                <span>Khuyến mãi</span>
                                <span>-<span x-text="promoType === 'Fixed' ? Number(promoValue).toLocaleString() + 'đ' : promoValue + '%'"></span></span>
                            </div>
                        </div>

                        <!-- Khuyến mãi section -->
                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-ticket-alt text-primary"></i> Mã Khuyến Mãi</label>
                                <a href="{{ route('public.promotions.index') }}" target="_blank" class="text-[9px] font-bold text-primary hover:underline">Xem mã giảm giá</a>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" name="promo_code" x-model="promoCode" placeholder="Nhập mã giảm giá..." class="form-control text-xs font-bold uppercase w-full">
                                <button type="button" @click="applyPromo()" class="btn btn-primary !px-4 !py-3 !text-[10px]">Áp dụng</button>
                            </div>
                            <p x-show="promoMessage" x-text="promoMessage" class="text-[10px] font-bold mt-3" :class="promoApplied ? 'text-primary' : 'text-rose-500'"></p>
                        </div>

                        <div class="pt-8 border-t-2 border-dashed border-slate-100 mb-10">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tổng chi phí dự kiến</p>
                                    <h3 class="text-4xl font-black text-primary tracking-tighter" x-text="getTotalPrice().toLocaleString() + 'đ'">{{ number_format($departure->price_override ?? $departure->tour->base_price) }}đ</h3>
                                </div>
                            </div>
                        </div>

                        <div class="relative" x-data="{ showTip: false }">
                            <button type="submit" 
                                    @mouseenter="if(!agreed) showTip = true" 
                                    @mouseleave="showTip = false"
                                    class="btn btn-primary w-full py-5 rounded-2xl !text-xs shadow-sm group relative overflow-hidden" 
                                    :disabled="!agreed" 
                                    :class="!agreed ? 'opacity-50 cursor-not-allowed grayscale pointer-events-none' : ''">
                                <span class="relative z-10 flex items-center justify-center gap-3">
                                    HOÀN TẤT ĐẶT TOUR <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                                </span>
                            </button>
                            
                            <!-- Tooltip for disabled state -->
                            <div x-show="!agreed" class="mt-4 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-4 animate-bounce">
                                <div class="w-6 h-6 bg-rose-500 text-white rounded-lg flex items-center justify-center text-[10px]">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest leading-none">Vui lòng đồng ý với điều khoản</p>
                            </div>
                        </div>
                        
                        <p class="text-center text-[9px] font-bold text-slate-400 mt-8 italic px-4 leading-relaxed">Vui lòng kiểm tra kỹ thông tin hành khách trước khi bấm xác nhận.</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        @foreach(['Shield' => 'Bảo mật', 'Headset' => 'Hỗ trợ', 'Check' => 'Chính xác'] as $icon => $label)
                        <div class="bg-white p-4 rounded-3xl border border-slate-100 text-center shadow-sm">
                            <i class="fas fa-{{ strtolower($icon) }} text-primary text-sm mb-2 block"></i>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </form>
</section>
@endsection

@section('styles')
<style>
    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>
@endsection
