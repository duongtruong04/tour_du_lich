@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('styles')
<style>
    .auth-section {
        position: relative;
        padding: 20px 0;
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
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
        padding: 0.85rem 1rem 0.85rem 3rem;
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
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }
    .btn-auth-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.4);
    }
    .btn-auth-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    .status-message {
        background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        border: 1px solid #bbf7d0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .status-message p {
        color: #166534;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.6;
        margin: 0;
    }
    .error-message {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 1rem;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .error-message p {
        color: #991b1b;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0;
    }
    .shield-icon-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .shield-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: inherit;
        background: rgba(16, 185, 129, 0.15);
        animation: shieldPulse 2s ease-in-out infinite;
    }
    @keyframes shieldPulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.15); opacity: 0; }
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
                <div class="auth-card p-6 sm:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="shield-icon-wrapper w-16 h-16 bg-emerald-50 text-primary rounded-2xl mb-5 mx-auto">
                            <div class="shield-pulse rounded-2xl"></div>
                            <i class="fas fa-key text-2xl relative z-10"></i>
                        </div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Quên mật khẩu?</h1>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed">
                            Nhập email đăng ký của bạn. Chúng tôi sẽ gửi mã xác thực 6 số để đặt lại mật khẩu.
                        </p>
                    </div>

                    {{-- Thông báo chung (luôn hiển thị cùng 1 message dù email tồn tại hay không) --}}
                    @if(session('status'))
                    <div class="status-message">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Validation errors --}}
                    @if($errors->any())
                    <div class="error-message">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle text-rose-500"></i>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" id="forgot-form">
                        @csrf
                        <div class="space-y-5">
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
                                <span id="btn-text"><i class="fas fa-paper-plane mr-2"></i>Gửi mã đặt lại mật khẩu</span>
                                <span id="btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang gửi yêu cầu...</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-8">
                        <a href="{{ route('login') }}" class="text-xs font-black text-primary uppercase tracking-widest hover:underline inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left text-[9px]"></i>
                            Quay lại đăng nhập
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
    document.getElementById('forgot-form').addEventListener('submit', function() {
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-load').style.display = 'inline';
        document.getElementById('submit-btn').disabled = true;
    });
</script>
@endsection
