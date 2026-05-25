@extends('layouts.app')

@section('title', 'Đăng nhập quản trị viên')

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
        background: radial-gradient(circle, rgba(13, 148, 136, 0.05) 0%, transparent 70%);
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
        color: #0d9488;
        transition: color 0.3s;
    }
    .auth-input {
        width: 100%;
        padding: 0.85rem 1rem 0.85rem 3rem;
        background: #f0fdfa;
        border: 2px solid transparent;
        border-radius: 1.25rem;
        font-weight: 600;
        color: #134e4a;
        transition: all 0.3s;
    }
    .auth-input:focus {
        background: #fff;
        border-color: #0d9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        outline: none;
    }
    .btn-auth-admin {
        width: 100%;
        padding: 1.15rem;
        background: #0d9488;
        color: white;
        border-radius: 1.25rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        transition: all 0.3s;
    }
    .btn-auth-admin:hover {
        background: #0f766e;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(13, 148, 136, 0.4);
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="auth-bg-blob top-0 left-0"></div>
    <div class="auth-bg-blob bottom-0 right-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="auth-card p-6 sm:p-10">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-50 text-teal-600 rounded-3xl mb-4 group transition-transform hover:rotate-12 duration-500">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>
                        <h1 class="text-xl font-black text-teal-900 tracking-tighter uppercase mb-2">Quản trị hệ thống</h1>
                        <p class="text-teal-600 font-bold uppercase tracking-widest text-[9px]">Portal bảo mật dành cho quản trị viên</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="admin" value="1">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-teal-800 uppercase tracking-widest mb-3 ml-1">Email quản trị</label>
                                <div class="auth-input-group">
                                    <input type="email" name="email" required value="{{ old('email') }}"
                                        class="auth-input"
                                        placeholder="admin@travel.com">
                                    <i class="far fa-envelope auth-input-icon"></i>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-[10px] text-rose-500 font-bold ml-1 italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-teal-800 uppercase tracking-widest mb-3 ml-1">Mật khẩu</label>
                                <div class="auth-input-group">
                                    <input type="password" name="password" required
                                        class="auth-input"
                                        placeholder="••••••••">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" class="w-4 h-4 rounded border-teal-200 text-teal-600 focus:ring-teal-500 cursor-pointer">
                                    <span class="ml-2 text-[10px] font-black text-teal-800 uppercase tracking-widest group-hover:text-teal-600 transition-colors">Ghi nhớ</span>
                                </label>
                                <a href="{{ route('home') }}" class="text-[10px] font-black text-teal-600 hover:text-teal-800 uppercase tracking-widest">← Về trang chủ</a>
                            </div>

                            <button type="submit" class="btn-auth-admin">
                                <i class="fas fa-sign-in-alt mr-2"></i>Đăng Nhập Hệ Thống
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="mt-8 text-center">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">
                        &copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Tour Travel' }}. Secure Environment.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
