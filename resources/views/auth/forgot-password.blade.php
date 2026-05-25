@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('styles')
<style>
    .auth-section {
        position: relative;
        padding: 40px 0;
        overflow: visible;
    }
    .auth-bg-blob {
        position: absolute;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        z-index: -1;
        filter: blur(60px);
    }
    .auth-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .auth-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.12);
    }
    .auth-input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .auth-input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: color 0.3s;
    }
    .auth-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3.5rem;
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 1rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    .auth-input:focus {
        background: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .auth-input:focus + .auth-input-icon {
        color: #10b981;
    }
    .btn-auth-primary {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }
    .btn-auth-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.4);
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-bg-blob top-0 left-0"></div>
    <div class="auth-bg-blob bottom-0 right-0" style="background: radial-gradient(circle, rgba(34, 211, 238, 0.05) 0%, transparent 70%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <div class="auth-card p-8 sm:p-12">
                    <!-- Header -->
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 text-primary rounded-2xl mb-6">
                            <i class="fas fa-key text-2xl"></i>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Quên mật khẩu?</h1>
                        <p class="text-slate-500 font-medium">Nhập email đăng ký của bạn để nhận liên kết đặt lại mật khẩu.</p>
                    </div>

                    @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl mb-6">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                        
                        <!-- Fallback link in case SMTP isn't running -->
                        @if(session('debug_reset_url'))
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs">
                            <p class="font-black uppercase tracking-wider flex items-center gap-2 text-amber-700 mb-2">
                                <i class="fas fa-exclamation-triangle"></i> Cấu hình Mail Server chưa chạy
                            </p>
                            <p class="mb-3 leading-relaxed">Hệ thống không thể gửi email thực tế đến địa chỉ của bạn. Để kiểm tra và thử nghiệm tính năng nhanh chóng, vui lòng bấm trực tiếp vào liên kết bên dưới:</p>
                            <a href="{{ session('debug_reset_url') }}" class="inline-block px-4 py-2 bg-amber-600 text-white rounded-lg font-bold hover:bg-amber-700 transition-colors">
                                <i class="fas fa-magic mr-1"></i> Đặt lại mật khẩu ngay
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($errors->has('email'))
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="text-sm font-bold">{{ $errors->first('email') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" id="reset-request-form">
                        @csrf
                        <div class="space-y-6">
                            <!-- Email -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email đăng ký</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                       class="auth-input {{ $errors->has('email') ? 'border-rose-200' : '' }}" 
                                       placeholder="example@email.com" required autocomplete="email">
                                <i class="fas fa-envelope auth-input-icon"></i>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn-auth-primary" id="submit-btn">
                                <span id="btn-text">Gửi liên kết đặt lại</span>
                                <span id="btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang gửi yêu cầu...</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-8">
                        <a href="{{ route('login') }}" class="text-xs font-black text-primary uppercase tracking-widest hover:underline">
                            ← Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.getElementById('reset-request-form').addEventListener('submit', function() {
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-load').style.display = 'inline';
        document.getElementById('submit-btn').disabled = true;
    });
</script>
@endsection
