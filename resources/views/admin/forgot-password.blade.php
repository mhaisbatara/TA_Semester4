<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password - SiObe</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --green-deep:  #059669;
        --green-mid:   #10b981;
        --green-light: #34d399;
    }
    * { box-sizing: border-box; }
    body {
        font-family: 'DM Sans', sans-serif;
        background: #f0fdf7;
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .bg-mesh {
        position: fixed; inset: 0; z-index: 0;
        background:
            radial-gradient(ellipse 80% 60% at 15% 15%, rgba(16,185,129,0.13) 0%, transparent 60%),
            radial-gradient(ellipse 60% 70% at 85% 85%, rgba(5,150,105,0.11) 0%, transparent 60%),
            #f0fdf9;
    }
    .grid-pattern {
        position: fixed; inset: 0; z-index: 0;
        background-image:
            linear-gradient(rgba(16,185,129,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(16,185,129,0.04) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 40%, transparent 80%);
    }
    .orb { position:fixed; border-radius:50%; filter:blur(60px); opacity:0.45; pointer-events:none; z-index:0; animation:floatOrb 9s ease-in-out infinite; }
    .orb-1 { width:320px;height:320px;background:rgba(16,185,129,0.17);top:-70px;left:-70px;animation-delay:0s; }
    .orb-2 { width:240px;height:240px;background:rgba(5,150,105,0.13);bottom:-50px;right:-50px;animation-delay:3.5s; }
    .orb-3 { width:160px;height:160px;background:rgba(52,211,153,0.10);top:38%;right:8%;animation-delay:1.8s; }
    @keyframes floatOrb { 0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-18px) scale(1.04);} }

    .login-card {
        position:relative; z-index:10;
        width:100%; max-width:430px;
        background:rgba(255,255,255,0.84);
        backdrop-filter:blur(24px) saturate(180%);
        -webkit-backdrop-filter:blur(24px) saturate(180%);
        border-radius:28px;
        border:1px solid rgba(16,185,129,0.18);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 12px 40px rgba(16,185,129,0.10), 0 40px 80px rgba(16,185,129,0.05), inset 0 1px 0 rgba(255,255,255,0.9);
        padding:44px 40px 38px;
        animation:slideUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
    }
    @keyframes slideUp { from{opacity:0;transform:translateY(26px) scale(0.97);}to{opacity:1;transform:translateY(0) scale(1);} }
    .card-accent { position:absolute;top:0;left:40px;right:40px;height:2px;border-radius:0 0 4px 4px;background:linear-gradient(90deg,transparent,var(--green-mid),transparent);opacity:0.65; }

    .logo-wrap {
        width:62px;height:62px;border-radius:18px;
        background:linear-gradient(135deg,var(--green-deep),var(--green-light));
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 18px;
        box-shadow:0 8px 24px rgba(16,185,129,0.32),0 2px 4px rgba(16,185,129,0.18);
        animation:popIn 0.65s 0.25s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes popIn { from{opacity:0;transform:scale(0.5);}to{opacity:1;transform:scale(1);} }

    /* Step indicator */
    .steps { display:flex;align-items:center;justify-content:center;margin:20px 0 24px; }
    .step-item { display:flex;flex-direction:column;align-items:center; }
    .step-dot {
        width:32px;height:32px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;
        transition:all 0.4s ease;
    }
    .step-dot.active  { background:linear-gradient(135deg,var(--green-deep),var(--green-mid));color:white;box-shadow:0 4px 12px rgba(16,185,129,0.35); }
    .step-dot.done    { background:var(--green-mid);color:white; }
    .step-dot.inactive{ background:rgba(16,185,129,0.1);color:#9ab5a8;border:1.5px solid rgba(16,185,129,0.2); }
    .step-line { width:48px;height:2px;background:rgba(16,185,129,0.15);border-radius:2px;overflow:hidden; }
    .step-line-fill { height:100%;background:linear-gradient(90deg,var(--green-deep),var(--green-mid));border-radius:2px;transition:width 0.5s ease; }
    .step-label { font-size:11px;color:#8a9e94;text-align:center;margin-top:4px;font-weight:500; }
    .step-connector { display:flex;flex-direction:column;align-items:center;padding-bottom:18px; }

    /* Typography */
    .brand-name { font-family:'Outfit',sans-serif;font-size:24px;font-weight:700;color:#0f1a15;letter-spacing:-0.4px; }
    .brand-sub { font-size:12px;color:#6b7c74;letter-spacing:0.6px;text-transform:uppercase;margin-top:3px; }
    .section-title { font-family:'Outfit',sans-serif;font-size:18px;font-weight:600;color:#1a2e25; }
    .section-sub { font-size:13px;color:#8a9e94;margin-top:5px;line-height:1.5; }
    .divider { width:32px;height:2px;background:linear-gradient(90deg,var(--green-mid),var(--green-light));border-radius:2px;margin:12px auto 16px;opacity:0.55; }

    /* Input */
    .input-wrap { position:relative;margin-top:14px; }
    .input-icon { position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#9ab5a8;font-size:14px;pointer-events:none;transition:color 0.2s;z-index:1; }
    .input-field {
        width:100%;padding:13px 16px 13px 44px;
        border:1.5px solid rgba(16,185,129,0.2);border-radius:14px;
        background:rgba(240,253,247,0.6);
        font-family:'DM Sans',sans-serif;font-size:14px;color:#1a2e25;
        outline:none;transition:all 0.25s ease;
        box-shadow:0 1px 3px rgba(0,0,0,0.03) inset;
    }
    .input-field::placeholder { color:#b0c8be; }
    .input-field:focus { border-color:var(--green-mid);background:white;box-shadow:0 0 0 4px rgba(16,185,129,0.10),0 1px 3px rgba(0,0,0,0.04) inset; }
    .input-wrap:focus-within .input-icon { color:var(--green-mid); }

    /* Strength */
    .strength-bar { display:flex;gap:4px;margin-top:8px; }
    .strength-seg { flex:1;height:3px;border-radius:2px;background:rgba(16,185,129,0.12);transition:background 0.3s; }
    .strength-seg.weak   { background:#f87171; }
    .strength-seg.medium { background:#fbbf24; }
    .strength-seg.strong { background:var(--green-mid); }
    .strength-label { font-size:11px;color:#8a9e94;margin-top:4px; }

    /* Button */
    .btn-primary {
        width:100%;padding:14px;margin-top:22px;
        border:none;border-radius:14px;
        background:linear-gradient(135deg,var(--green-deep) 0%,var(--green-mid) 55%,var(--green-light) 100%);
        background-size:200% 200%;background-position:0% 50%;
        color:white;font-family:'Outfit',sans-serif;font-size:15px;font-weight:600;letter-spacing:0.3px;
        cursor:pointer;transition:all 0.3s ease;
        box-shadow:0 4px 14px rgba(16,185,129,0.32),0 1px 3px rgba(16,185,129,0.18);
        position:relative;overflow:hidden;
    }
    .btn-primary:hover { background-position:100% 50%;box-shadow:0 6px 20px rgba(16,185,129,0.42);transform:translateY(-1px); }
    .btn-primary:active { transform:translateY(1px); }

    /* Alerts */
    .alert { padding:11px 14px;border-radius:12px;font-size:13px;display:flex;align-items:flex-start;gap:9px;margin-bottom:14px;animation:slideDown 0.3s ease; }
    .alert-error   { background:rgba(254,242,242,0.9);border:1px solid rgba(252,165,165,0.5);color:#dc2626; }
    .alert-success { background:rgba(240,253,244,0.95);border:1px solid rgba(134,239,172,0.5);color:#16a34a; }
    @keyframes slideDown { from{opacity:0;transform:translateY(-8px);}to{opacity:1;transform:translateY(0);} }

    /* Success icon */
    .success-icon {
        width:72px;height:72px;border-radius:50%;
        background:linear-gradient(135deg,var(--green-deep),var(--green-light));
        display:flex;align-items:center;justify-content:center;
        margin:0 auto 20px;
        box-shadow:0 8px 24px rgba(16,185,129,0.35);
        animation:successPop 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
    }
    @keyframes successPop { from{opacity:0;transform:scale(0.4);}to{opacity:1;transform:scale(1);} }

    .back-link { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#5a7568;text-decoration:none;transition:color 0.2s;margin-top:18px; }
    .back-link:hover { color:var(--green-deep); }
    .footer-text { font-size:11.5px;color:#aac2b8;margin-top:22px;letter-spacing:0.2px; }
    .eye-btn { position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ab5a8;font-size:14px;padding:4px;transition:color 0.2s; }
    .eye-btn:hover { color:var(--green-mid); }
</style>
</head>
<body>

<div class="bg-mesh"></div>
<div class="grid-pattern"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

{{-- Tentukan step berdasarkan session --}}
@php
    $step = 1;
    if (session('reset_success'))   $step = 3;
    elseif (session('verified_email')) $step = 2;
@endphp

<div class="login-card">
    <div class="card-accent"></div>

    {{-- LOGO --}}
    <div class="text-center">
        <div class="logo-wrap">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2.5L4 5.9v5.725c0 4.8 3.56 9.28 8 10.43 4.44-1.15 8-5.63 8-10.43V5.9L12 2.5z" fill="rgba(255,255,255,0.2)" stroke="white" stroke-width="0.5"/>
                <rect x="10.75" y="7.2"  width="2.5" height="7.5" rx="1.2" fill="white"/>
                <rect x="8.25"  y="9.75" width="7.5" height="2.5" rx="1.2" fill="white"/>
            </svg>
        </div>
        <div class="brand-name">SiObe</div>
        <div class="brand-sub">Sistem Deteksi Obesitas</div>
        <div class="divider"></div>
    </div>

    {{-- STEP INDICATOR --}}
    <div class="steps">
        <div class="step-item">
            <div class="step-dot {{ $step > 1 ? 'done' : ($step == 1 ? 'active' : 'inactive') }}">
                @if($step > 1) <i class="fas fa-check" style="font-size:11px"></i> @else 1 @endif
            </div>
            <div class="step-label">Email</div>
        </div>
        <div class="step-connector">
            <div class="step-line"><div class="step-line-fill" style="width:{{ $step > 1 ? '100%' : '0%' }}"></div></div>
        </div>
        <div class="step-item">
            <div class="step-dot {{ $step > 2 ? 'done' : ($step == 2 ? 'active' : 'inactive') }}">
                @if($step > 2) <i class="fas fa-check" style="font-size:11px"></i> @else 2 @endif
            </div>
            <div class="step-label">Password Baru</div>
        </div>
        <div class="step-connector">
            <div class="step-line"><div class="step-line-fill" style="width:{{ $step > 2 ? '100%' : '0%' }}"></div></div>
        </div>
        <div class="step-item">
            <div class="step-dot {{ $step == 3 ? 'done' : 'inactive' }}">
                <i class="fas fa-check" style="font-size:11px"></i>
            </div>
            <div class="step-label">Selesai</div>
        </div>
    </div>

    {{-- ==================== STEP 1 — EMAIL ==================== --}}
    @if($step == 1)
    <div class="text-center mb-5">
        <div class="section-title">Verifikasi Email</div>
        <div class="section-sub">Masukkan email admin yang terdaftar<br>untuk memulai reset password.</div>
    </div>

    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-circle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-circle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.forgot-password.verify') }}">
        @csrf
        <div class="input-wrap">
            <input type="email" name="email" required autocomplete="email"
                   value="{{ old('email') }}"
                   class="input-field" placeholder="Alamat Email Admin">
            <i class="fas fa-envelope input-icon"></i>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-shield-check" style="margin-right:8px;opacity:0.85"></i>
            Verifikasi Email
        </button>
    </form>
    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="back-link">
            <i class="fas fa-arrow-left" style="font-size:11px"></i>
            Kembali ke Login
        </a>
    </div>
    @endif

    {{-- ==================== STEP 2 — PASSWORD BARU ==================== --}}
    @if($step == 2)
    <div class="text-center mb-5">
        <div class="section-title">Password Baru</div>
        <div class="section-sub">
            Buat password baru untuk akun<br>
            <strong style="color:#1a2e25">{{ session('verified_email') }}</strong>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-circle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-circle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.forgot-password.reset') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('verified_email') }}">

        <div class="input-wrap">
            <input type="password" name="password" id="newPass" required
                   class="input-field" placeholder="Password Baru"
                   oninput="checkStrength(this.value)">
            <i class="fas fa-lock input-icon"></i>
            <button type="button" class="eye-btn" onclick="toggleVis('newPass','eye1')">
                <i class="fas fa-eye" id="eye1"></i>
            </button>
        </div>
        <div class="strength-bar">
            <div class="strength-seg" id="s1"></div>
            <div class="strength-seg" id="s2"></div>
            <div class="strength-seg" id="s3"></div>
            <div class="strength-seg" id="s4"></div>
        </div>
        <div class="strength-label" id="strengthLabel">Minimal 8 karakter</div>

        <div class="input-wrap" style="margin-top:16px">
            <input type="password" name="password_confirmation" id="confPass" required
                   class="input-field" placeholder="Konfirmasi Password Baru">
            <i class="fas fa-lock-open input-icon"></i>
            <button type="button" class="eye-btn" onclick="toggleVis('confPass','eye2')">
                <i class="fas fa-eye" id="eye2"></i>
            </button>
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-key" style="margin-right:8px;opacity:0.85"></i>
            Simpan Password Baru
        </button>
    </form>
    @endif

    {{-- ==================== STEP 3 — SUKSES ==================== --}}
    @if($step == 3)
    <div class="text-center" style="padding:8px 0 16px">
        <div class="success-icon">
            <i class="fas fa-check" style="color:white;font-size:28px"></i>
        </div>
        <div class="section-title" style="font-size:20px">Password Berhasil Diubah!</div>
        <div class="section-sub" style="margin-top:8px">
            Password akun admin telah berhasil diperbarui.<br>
            Silakan login dengan password baru Anda.
        </div>
    </div>
    <a href="{{ route('admin.login') }}"
       class="btn-primary"
       style="display:block;text-align:center;text-decoration:none;margin-top:24px">
        <i class="fas fa-arrow-right-to-bracket" style="margin-right:8px;opacity:0.85"></i>
        Masuk Sekarang
    </a>
    @endif

    <p class="footer-text text-center">© 2026 SiObe System</p>
</div>

<script>
function checkStrength(val) {
    const segs  = ['s1','s2','s3','s4'];
    const label = document.getElementById('strengthLabel');
    let score   = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = ['','weak','medium','strong','strong'];
    const labels = ['Minimal 8 karakter','Lemah — tambahkan huruf besar','Sedang — tambahkan angka','Kuat!','Sangat Kuat!'];
    const colors = ['#8a9e94','#f87171','#fbbf24','#10b981','#059669'];
    segs.forEach((id,i) => {
        document.getElementById(id).className = 'strength-seg ' + (i < score ? levels[score] : '');
    });
    label.textContent = labels[score];
    label.style.color = colors[score];
}
function toggleVis(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
</body>
</html>
