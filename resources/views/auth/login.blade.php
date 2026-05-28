<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - SiObe</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --green-deep:   #059669;
        --green-mid:    #10b981;
        --green-light:  #34d399;
        --green-pale:   #d1fae5;
        --green-glow:   rgba(16,185,129,0.18);
        --green-glow2:  rgba(16,185,129,0.08);
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: #f0fdf7;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* ─── MESH BACKGROUND ─── */
    .bg-mesh {
        position: fixed;
        inset: 0;
        z-index: 0;
        background:
            radial-gradient(ellipse 80% 60% at 10% 10%,  rgba(16,185,129,0.14) 0%, transparent 60%),
            radial-gradient(ellipse 60% 70% at 90% 90%,  rgba(5,150,105,0.12)  0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 50% 50%,  rgba(52,211,153,0.06) 0%, transparent 60%),
            #f0fdf9;
    }

    /* ─── FLOATING ORBS ─── */
    .orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.5;
        pointer-events: none;
        z-index: 0;
        animation: float 8s ease-in-out infinite;
    }
    .orb-1 { width: 340px; height: 340px; background: rgba(16,185,129,0.18); top: -80px; left: -80px; animation-delay: 0s; }
    .orb-2 { width: 260px; height: 260px; background: rgba(5,150,105,0.14);  bottom: -60px; right: -60px; animation-delay: 3s; }
    .orb-3 { width: 180px; height: 180px; background: rgba(52,211,153,0.12); top: 40%; right: 10%; animation-delay: 1.5s; }

    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50%       { transform: translateY(-20px) scale(1.04); }
    }

    /* ─── DECORATIVE GRID ─── */
    .grid-pattern {
        position: fixed;
        inset: 0;
        z-index: 0;
        background-image:
            linear-gradient(rgba(16,185,129,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(16,185,129,0.04) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 40%, transparent 80%);
    }

    /* ─── CARD ─── */
    .login-card {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 420px;
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border-radius: 28px;
        border: 1px solid rgba(16,185,129,0.2);
        box-shadow:
            0 4px 6px rgba(0,0,0,0.02),
            0 12px 40px rgba(16,185,129,0.10),
            0 40px 80px rgba(16,185,129,0.06),
            inset 0 1px 0 rgba(255,255,255,0.9);
        padding: 44px 40px 36px;
        animation: slideUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(28px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)   scale(1); }
    }

    /* top accent line */
    .card-accent {
        position: absolute;
        top: 0; left: 40px; right: 40px;
        height: 2px;
        border-radius: 0 0 4px 4px;
        background: linear-gradient(90deg, transparent, var(--green-mid), transparent);
        opacity: 0.7;
    }

    /* ─── LOGO ─── */
    .logo-wrap {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--green-deep), var(--green-light));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 24px rgba(16,185,129,0.35), 0 2px 4px rgba(16,185,129,0.2);
        animation: logoPop 0.7s 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    @keyframes logoPop {
        from { opacity: 0; transform: scale(0.6); }
        to   { opacity: 1; transform: scale(1); }
    }

    .logo-wrap svg {
        width: 32px;
        height: 32px;
        fill: white;
    }

    /* ─── TITLES ─── */
    .brand-name {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: #0f1a15;
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .brand-sub {
        font-size: 12.5px;
        font-weight: 400;
        color: #6b7c74;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .divider {
        width: 36px;
        height: 2px;
        background: linear-gradient(90deg, var(--green-mid), var(--green-light));
        border-radius: 2px;
        margin: 14px auto 18px;
        opacity: 0.6;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 600;
        color: #1a2e25;
    }

    .section-sub {
        font-size: 13px;
        color: #8a9e94;
        margin-top: 4px;
    }

    /* ─── INPUTS ─── */
    .input-wrap {
        position: relative;
        margin-top: 14px;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ab5a8;
        font-size: 14px;
        pointer-events: none;
        transition: color 0.2s;
    }

    .input-field {
        width: 100%;
        padding: 13px 16px 13px 44px;
        border: 1.5px solid rgba(16,185,129,0.2);
        border-radius: 14px;
        background: rgba(240,253,247,0.6);
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: #1a2e25;
        outline: none;
        transition: all 0.25s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03) inset;
    }

    .input-field::placeholder { color: #b0c8be; }

    .input-field:focus {
        border-color: var(--green-mid);
        background: white;
        box-shadow: 0 0 0 4px rgba(16,185,129,0.10), 0 1px 3px rgba(0,0,0,0.04) inset;
    }

    .input-field:focus + .input-icon,
    .input-wrap:focus-within .input-icon {
        color: var(--green-mid);
    }

    /* fix icon z-order */
    .input-wrap .input-icon { z-index: 1; }

    /* ─── CHECKBOX & FORGOT ─── */
    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #5a7568;
        cursor: pointer;
        user-select: none;
    }

    .remember-label input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: var(--green-mid);
        border-radius: 4px;
        cursor: pointer;
    }

    .forgot-link {
        font-size: 13px;
        font-weight: 500;
        color: var(--green-deep);
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-link:hover { color: var(--green-mid); }

    /* ─── BUTTON ─── */
    .btn-masuk {
        width: 100%;
        padding: 14px;
        margin-top: 22px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 60%, var(--green-light) 100%);
        background-size: 200% 200%;
        background-position: 0% 50%;
        color: white;
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow:
            0 4px 14px rgba(16,185,129,0.35),
            0 1px 3px rgba(16,185,129,0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-masuk::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn-masuk:hover {
        background-position: 100% 50%;
        box-shadow: 0 6px 20px rgba(16,185,129,0.45), 0 2px 6px rgba(16,185,129,0.25);
        transform: translateY(-1px);
    }

    .btn-masuk:hover::after { opacity: 1; }

    .btn-masuk:active {
        transform: translateY(1px);
        box-shadow: 0 2px 8px rgba(16,185,129,0.25);
    }

    /* ─── FOOTER ─── */
    .footer-text {
        font-size: 11.5px;
        color: #aac2b8;
        margin-top: 24px;
        letter-spacing: 0.2px;
    }

    /* ─── ERROR ─── */
    .error-box {
        background: rgba(254,242,242,0.9);
        border: 1px solid rgba(252,165,165,0.5);
        color: #dc2626;
        padding: 11px 14px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%       { transform: translateX(-6px); }
        40%       { transform: translateX(6px); }
        60%       { transform: translateX(-4px); }
        80%       { transform: translateX(4px); }
    }
</style>
</head>

<body>

<!-- BACKGROUND LAYERS -->
<div class="bg-mesh"></div>
<div class="grid-pattern"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- LOGIN CARD -->
<div class="login-card">
    <div class="card-accent"></div>

    <!-- LOGO + BRAND -->
    <div class="text-center">
        <div class="logo-wrap">
            <!-- Shield + Cross icon (matches the second image) -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 1.5L3 5.25v6.375c0 5.18 3.844 10.025 9 11.25 5.156-1.225 9-6.07 9-11.25V5.25L12 1.5z"/>
                <path d="M10.5 9h3v-2.5h-3V9zm0 5.5h3V10.5h-3V14.5z" fill="rgba(255,255,255,0.3)"/>
                <!-- cross symbol -->
                <rect x="10.75" y="7.5"  width="2.5" height="7" rx="1.1" fill="white"/>
                <rect x="8.5"   y="9.75" width="7"   height="2.5" rx="1.1" fill="white"/>
            </svg>
        </div>

        <div class="brand-name">SiObe</div>
        <div class="brand-sub">Sistem Deteksi Obesitas</div>
        <div class="divider"></div>

        <div class="section-title">Masuk ke Dashboard</div>
        <div class="section-sub">Silakan login untuk melanjutkan</div>
    </div>

    <!-- ERROR -->
    @if(session('error'))
    <div class="error-box mt-5">
        <i class="fas fa-circle-exclamation text-red-400"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- FORM -->
    <form method="POST" action="{{ route('admin.login.post') }}" style="margin-top:24px">
        @csrf

        <!-- Email -->
        <div class="input-wrap">
            <input type="email" name="email" required
                   class="input-field"
                   placeholder="Alamat Email">
            <i class="fas fa-envelope input-icon"></i>
        </div>

        <!-- Password -->
        <div class="input-wrap">
            <input type="password" name="password" id="passInput" required
                   class="input-field"
                   placeholder="Password">
            <i class="fas fa-lock input-icon"></i>
            <button type="button"
                    onclick="togglePass()"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ab5a8;font-size:14px;padding:4px;"
                    id="eyeBtn">
                <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
        </div>

        <!-- Remember + Forgot -->
        <div class="remember-row">
            <label class="remember-label">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>
            <a href="{{ route('admin.forgot-password.form') }}" class="forgot-link">Lupa password?</a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-masuk">
            <i class="fas fa-arrow-right-to-bracket" style="margin-right:8px;opacity:0.85;"></i>
            Masuk
        </button>
    </form>

    <p class="footer-text text-center">© 2026 SiObe System</p>
</div>

<script>
function togglePass() {
    const input = document.getElementById('passInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>

</body>
</html>
