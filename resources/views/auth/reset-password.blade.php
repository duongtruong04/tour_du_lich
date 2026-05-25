@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

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
                            <i class="fas fa-lock-open text-2xl"></i>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Đặt lại mật khẩu</h1>
                        <p class="text-slate-500 font-medium">Nhập mật khẩu mới của bạn để hoàn tất việc cập nhật.</p>
                    </div>

                    @if($errors->any())
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm font-bold">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" id="reset-password-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="space-y-5">
                            <!-- Email -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $email) }}" 
                                       class="auth-input" placeholder="example@email.com" required readonly>
                                <i class="fas fa-envelope auth-input-icon"></i>
                            </div>

                            <!-- New Password -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" name="password" id="new-password" 
                                           class="auth-input" placeholder="••••••••" required autocomplete="new-password">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" onclick="togglePwd('new-password','new-eye')">
                                        <i class="fas fa-eye" id="new-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="auth-input-group">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Xác nhận mật khẩu mới</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="confirm-password" 
                                           class="auth-input" placeholder="••••••••" required autocomplete="new-password">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors" onclick="togglePwd('confirm-password','confirm-eye')">
                                        <i class="fas fa-eye" id="confirm-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn-auth-primary" id="submit-btn">
                                <span id="btn-text">Đặt lại mật khẩu</span>
                                <span id="btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang xử lý...</span>
                            </button>
                        </div>
                    </form>
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
    document.getElementById('reset-password-form').addEventListener('submit', function() {
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-load').style.display = 'inline';
        document.getElementById('submit-btn').disabled = true;
    });
</script>
@endsection
