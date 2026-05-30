@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

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
    /* OTP Input Styling */
    .otp-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 1rem;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: 0.75rem;
        text-align: center;
        font-family: 'Courier New', monospace;
        color: #059669;
        transition: all 0.3s;
    }
    .otp-input:focus {
        background: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .otp-input::placeholder {
        font-size: 1rem;
        letter-spacing: 0.3rem;
        color: #cbd5e1;
        font-weight: 500;
    }
    .lock-icon-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .lock-shimmer {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, transparent 30%, rgba(16,185,129,0.15) 50%, transparent 70%);
        animation: lockShimmer 3s ease-in-out infinite;
    }
    @keyframes lockShimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
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
                        <div class="lock-icon-wrapper w-16 h-16 bg-emerald-50 text-primary rounded-2xl mb-5 mx-auto overflow-hidden">
                            <div class="lock-shimmer rounded-2xl"></div>
                            <i class="fas fa-lock-open text-2xl relative z-10"></i>
                        </div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Đặt lại mật khẩu</h1>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed">
                            Nhập mã xác thực 6 số đã gửi đến email của bạn cùng với mật khẩu mới.
                        </p>
                    </div>

                    {{-- Thông báo chung --}}
                    @if(session('status'))
                    <div class="status-message">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-emerald-500 mt-0.5"></i>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Validation errors --}}
                    @if($errors->any())
                    <div class="error-message">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-rose-400 text-xs"></i>
                                    <p>{{ $error }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" id="reset-password-form">
                        @csrf
                        <div class="space-y-5">
                            <!-- Email -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email</label>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email', $email ?? '') }}"
                                       class="auth-input {{ $errors->has('email') ? 'border-rose-200' : '' }}"
                                       placeholder="example@email.com" required autocomplete="email">
                                <i class="fas fa-envelope auth-input-icon"></i>
                            </div>

                            <!-- OTP Code -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mã xác thực (6 số)</label>
                                <input type="text" name="code" id="otp-code"
                                       class="otp-input {{ $errors->has('code') ? 'border-rose-200' : '' }}"
                                       placeholder="● ● ● ● ● ●"
                                       maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                                       autocomplete="one-time-code" required>
                            </div>

                            <!-- New Password -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" name="password" id="new-password"
                                           class="auth-input {{ $errors->has('password') ? 'border-rose-200' : '' }}"
                                           placeholder="••••••••" required autocomplete="new-password" minlength="8">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" onclick="togglePwd('new-password','new-eye')">
                                        <i class="fas fa-eye" id="new-eye"></i>
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 ml-1 font-medium">Tối thiểu 8 ký tự, gồm ít nhất 1 chữ cái và 1 chữ số.</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Xác nhận mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="confirm-password"
                                           class="auth-input"
                                           placeholder="••••••••" required autocomplete="new-password">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" onclick="togglePwd('confirm-password','confirm-eye')">
                                        <i class="fas fa-eye" id="confirm-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn-auth-primary" id="submit-btn">
                                <span id="btn-text"><i class="fas fa-shield-alt mr-2"></i>Cập nhật mật khẩu</span>
                                <span id="btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang xử lý...</span>
                            </button>
                        </div>
                    </form>

                    <div class="flex items-center justify-between mt-8">
                        <a href="{{ route('password.request') }}" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary transition-colors inline-flex items-center gap-1.5">
                            <i class="fas fa-redo-alt text-[8px]"></i>
                            Gửi lại mã
                        </a>
                        <a href="{{ route('login') }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline inline-flex items-center gap-1.5">
                            <i class="fas fa-arrow-left text-[8px]"></i>
                            Đăng nhập
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
    function togglePwd(inputId, iconId) {
        const el = document.getElementById(inputId);
        const ic = document.getElementById(iconId);
        el.type = el.type === 'password' ? 'text' : 'password';
        ic.className = el.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }

    // Auto-format OTP input: only allow digits
    document.getElementById('otp-code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
    });

    document.getElementById('reset-password-form').addEventListener('submit', function() {
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-load').style.display = 'inline';
        document.getElementById('submit-btn').disabled = true;
    });
</script>
@endsection
