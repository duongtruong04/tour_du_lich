@extends('layouts.app')

@section('title', 'Đăng nhập')

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
        padding: 0.75rem 1rem 0.75rem 3rem;
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
    .btn-auth-outline {
        width: 100%;
        padding: 0.85rem;
        background: white;
        color: #475569;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        font-weight: 700;
        transition: all 0.3s;
    }
    .btn-auth-outline:hover {
        border-color: #10b981;
        color: #10b981;
        background: #f0fdf4;
    }
    .auth-divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .auth-divider::before, .auth-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    .auth-divider span {
        padding: 0 1rem;
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
                <div class="auth-card p-6 sm:p-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-50 text-primary rounded-2xl mb-4">
                            <i class="fas fa-fingerprint text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Đăng nhập tài khoản</h1>
                        <p class="text-slate-500 font-medium text-sm">Chào mừng trở lại! Vui lòng nhập thông tin của bạn.</p>
                    </div>

                    @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 animate-bounce">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->has('email'))
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="text-sm font-bold">{{ $errors->first('email') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" id="login-form">
                        @csrf
                        <div class="space-y-4">
                            <!-- Email -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                       class="auth-input {{ $errors->has('email') ? 'border-rose-200' : '' }}" 
                                       placeholder="example@email.com" required autocomplete="email">
                                <i class="fas fa-envelope auth-input-icon"></i>
                            </div>

                            <!-- Password -->
                            <div class="auth-input-group">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mật khẩu</label>
                                    <a href="{{ route('password.request') }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Quên mật khẩu?</a>
                                </div>
                                <div class="relative">
                                    <input type="password" name="password" id="login-password" 
                                           class="auth-input" placeholder="••••••••" required autocomplete="current-password">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" onclick="togglePwd('login-password','login-eye')">
                                        <i class="fas fa-eye" id="login-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <label class="flex items-center gap-3 cursor-pointer group mb-6">
                                <div class="relative">
                                    <input type="checkbox" name="remember" class="peer hidden">
                                    <div class="w-5 h-5 border-2 border-slate-200 rounded-md peer-checked:bg-primary peer-checked:border-primary transition-all"></div>
                                    <i class="fas fa-check absolute inset-0 flex items-center justify-center text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-600 group-hover:text-primary transition-colors">Ghi nhớ đăng nhập</span>
                            </label>

                            <!-- Submit -->
                            <button type="submit" class="btn-auth-primary" id="login-submit">
                                <span id="login-btn-text">Đăng nhập ngay</span>
                                <span id="login-btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang xử lý...</span>
                            </button>
                        </div>
                    </form>

                    <div class="auth-divider">
                        <span>Chưa có tài khoản?</span>
                    </div>

                    <a href="{{ route('register') }}" class="btn-auth-outline flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus text-xs"></i>
                        <span>Tạo tài khoản miễn phí</span>
                    </a>
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
    document.getElementById('login-form').addEventListener('submit', function() {
        document.getElementById('login-btn-text').style.display = 'none';
        document.getElementById('login-btn-load').style.display = 'inline';
        document.getElementById('login-submit').disabled = true;
    });
</script>
@endsection
