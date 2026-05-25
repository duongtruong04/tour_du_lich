@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

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
        margin: 1rem 0;
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
    <div class="auth-bg-blob top-0 right-0"></div>
    <div class="auth-bg-blob bottom-0 left-0" style="background: radial-gradient(circle, rgba(34, 211, 238, 0.05) 0%, transparent 70%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="auth-card p-5 sm:p-6">
                    <!-- Header -->
                    <div class="text-center mb-3">
                        <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-50 text-primary rounded-xl mb-2">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight mb-1">Đăng ký tài khoản</h1>
                        <p class="text-slate-500 font-medium text-sm">Bắt đầu hành trình khám phá cùng chúng tôi!</p>
                    </div>

                    @if($errors->any())
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="text-sm font-bold">Vui lòng kiểm tra lại các thông tin đã nhập.</span>
                    </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" id="reg-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                            
                            <!-- Full Name -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Họ và tên <span class="text-rose-500">*</span></label>
                                <div class="auth-input-group">
                                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                                           class="auth-input {{ $errors->has('full_name') ? 'border-rose-200' : '' }}"
                                           placeholder="Nguyễn Văn A" required autocomplete="name">
                                    <i class="fas fa-user auth-input-icon"></i>
                                </div>
                                @error('full_name')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fas fa-info-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Email <span class="text-rose-500">*</span></label>
                                <div class="auth-input-group">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="auth-input {{ $errors->has('email') ? 'border-rose-200' : '' }}"
                                           placeholder="example@email.com" required autocomplete="email">
                                    <i class="fas fa-envelope auth-input-icon"></i>
                                </div>
                                @error('email')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fas fa-info-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Số điện thoại</label>
                                <div class="auth-input-group">
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                           class="auth-input" placeholder="0912 345 678" autocomplete="tel">
                                    <i class="fas fa-phone auth-input-icon"></i>
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Mật khẩu <span class="text-rose-500">*</span></label>
                                <div class="auth-input-group mb-1">
                                    <input type="password" name="password" id="reg-pass"
                                           class="auth-input {{ $errors->has('password') ? 'border-rose-200' : '' }}"
                                           placeholder="••••••••" required autocomplete="new-password"
                                           oninput="checkStrength(this.value)">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400" onclick="togglePwd('reg-pass','eye-reg1')">
                                        <i class="fas fa-eye" id="eye-reg1"></i>
                                    </button>
                                </div>
                                <!-- Strength indicators -->
                                <div class="flex gap-1.5 mb-2 px-1">
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full w-0 transition-all duration-500" id="sb1"></div></div>
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full w-0 transition-all duration-500" id="sb2"></div></div>
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full w-0 transition-all duration-500" id="sb3"></div></div>
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full w-0 transition-all duration-500" id="sb4"></div></div>
                                </div>
                                <p id="strength-label" class="text-[9px] font-black uppercase tracking-widest ml-1"></p>
                                @error('password')
                                <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1 flex items-center gap-1"><i class="fas fa-info-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Xác nhận <span class="text-rose-500">*</span></label>
                                <div class="auth-input-group">
                                    <input type="password" name="password_confirmation" id="reg-pass2"
                                           class="auth-input" placeholder="••••••••" required
                                           autocomplete="new-password" oninput="checkMatch()">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400" onclick="togglePwd('reg-pass2','eye-reg2')">
                                        <i class="fas fa-eye" id="eye-reg2"></i>
                                    </button>
                                </div>
                                <p id="match-msg" class="text-[9px] font-black uppercase tracking-widest ml-1 mt-1"></p>
                            </div>

                            <!-- Terms -->
                            <div class="md:col-span-2 mt-0">
                                <label class="flex items-start gap-3 p-2 bg-slate-50/50 border border-slate-100 rounded-xl cursor-pointer group hover:bg-white hover:border-primary transition-all">
                                    <div class="relative mt-0.5">
                                        <input type="checkbox" id="agree-terms" required class="peer hidden">
                                        <div class="w-5 h-5 border-2 border-slate-200 rounded-md peer-checked:bg-primary peer-checked:border-primary transition-all"></div>
                                        <i class="fas fa-check absolute inset-0 flex items-center justify-center text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-500 leading-relaxed">
                                        Tôi đã đọc và đồng ý với 
                                        <a href="#" class="text-primary hover:underline">Điều khoản dịch vụ</a> và 
                                        <a href="#" class="text-primary hover:underline">Chính sách bảo mật</a>.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="md:col-span-2 mt-2">
                                <button type="submit" class="btn-auth-primary" id="reg-submit">
                                    <span id="reg-btn-text">Tạo tài khoản ngay</span>
                                    <span id="reg-btn-load" style="display:none;"><i class="fas fa-circle-notch fa-spin mr-2"></i>Đang xử lý...</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="auth-divider">
                        <span>Đã có tài khoản?</span>
                    </div>

                    <a href="{{ route('login') }}" class="btn-auth-outline flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt text-xs"></i>
                        <span>Đăng nhập ngay</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function togglePwd(id, iconId) {
        const i = document.getElementById(id);
        const ic = document.getElementById(iconId);
        i.type = i.type === 'password' ? 'text' : 'password';
        ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }

    function checkStrength(val) {
        const fills = ['sb1','sb2','sb3','sb4'];
        const lbl = document.getElementById('strength-label');
        fills.forEach(id => { document.getElementById(id).style.width='0'; });
        if (!val) { lbl.textContent=''; return; }
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const colors = ['#f43f5e','#f97316','#eab308','#10b981'];
        const labels = ['Yếu','Trung bình','Mạnh','Rất mạnh'];
        for (let i=0; i<score; i++) {
            document.getElementById(fills[i]).style.cssText = `width:100%; background:${colors[score-1]};`;
        }
        lbl.textContent = labels[score-1] || '';
        lbl.style.color = colors[score-1] || '#94a3b8';
    }

    function checkMatch() {
        const p = document.getElementById('reg-pass').value;
        const c = document.getElementById('reg-pass2').value;
        const m = document.getElementById('match-msg');
        if (!c) { m.style.display='none'; return; }
        m.style.display = 'block';
        if (p === c) { m.textContent='✓ Mật khẩu khớp'; m.style.color='#10b981'; }
        else { m.textContent='✗ Mật khẩu chưa khớp'; m.style.color='#f43f5e'; }
    }

    document.getElementById('reg-form').addEventListener('submit', function(e) {
        if (!document.getElementById('agree-terms').checked) {
            e.preventDefault();
            alert('Vui lòng đồng ý với điều khoản dịch vụ.');
            return;
        }
        document.getElementById('reg-btn-text').style.display = 'none';
        document.getElementById('reg-btn-load').style.display = 'inline';
        document.getElementById('reg-submit').disabled = true;
    });
</script>
@endsection
