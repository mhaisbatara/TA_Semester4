<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - SiObe</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body {
    font-family: 'Inter', sans-serif;
}

/* OUTER GLOW RING */
.glow-wrapper {
    position: relative;
    padding: 3px;
    border-radius: 28px;
    background: linear-gradient(135deg, #10b981, #ffffff, #10b981);
    filter: blur(0px);
}

/* soft glow shadow */
.glow-wrapper::before {
    content: "";
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(16,185,129,0.25), transparent 60%);
    z-index: -1;
    filter: blur(25px);
    border-radius: 40px;
}

/* card */
.card {
    background: white;
    border-radius: 24px;
}

/* fade */
.fade {
    animation: fade 0.7s ease-out;
}
@keyframes fade {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 px-6">

<!-- BACKGROUND -->
<div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-gray-50"></div>

<!-- WRAPPER GLOW (LUAR KOTAK) -->
<div class="glow-wrapper w-full max-w-md fade">

    <!-- CARD PUTIH -->
    <div class="card p-8 shadow-xl">

        <!-- LOGO -->
        <div class="text-center mb-6">

            <div class="w-14 h-14 bg-emerald-500 rounded-2xl mx-auto flex items-center justify-center shadow-md">
                <i class="fas fa-heartbeat text-white"></i>
            </div>

            <h1 class="text-2xl font-bold mt-3 text-gray-800">SiObe</h1>
            <p class="text-gray-500 text-sm">Sistem Deteksi Obesitas</p>

        </div>

        <!-- TITLE -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                Masuk ke Dashboard
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Silakan login untuk melanjutkan
            </p>
        </div>

        <!-- ERROR -->
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-sm">
            {{ session('error') }}
        </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
            @csrf

            <input type="email" name="email" required
                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-emerald-400 outline-none"
                placeholder="Email">

            <input type="password" name="password" required
                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-emerald-400 outline-none"
                placeholder="Password">

            <div class="flex justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" class="accent-emerald-500">
                    Ingat saya
                </label>

                <a href="#" class="text-emerald-500 hover:text-emerald-600">
                    Lupa password?
                </a>
            </div>

            <button type="submit"
                class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-semibold shadow-md">
                Masuk
            </button>

        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
            © 2026 SiObe System
        </p>

    </div>

</div>

</body>
</html>
