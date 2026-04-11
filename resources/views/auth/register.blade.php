<!DOCTYPE html>

<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2E7D32",
                        "background-light": "#F5F7FA",
                        "background-dark": "#121417",
                        "neutral-light": "#E5E7EB",
                        "neutral-dark": "#374151",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display">
<!-- Top Navigation Bar -->
<header class="w-full bg-white dark:bg-neutral-dark border-b border-neutral-light dark:border-neutral-dark px-6 py-4">
<div class="max-w-7xl mx-auto flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-3xl">health_metrics</span>
<h1 class="text-slate-900 dark:text-slate-100 text-xl font-bold tracking-tight">Obesity Detection</h1>
</div>
<div class="hidden md:flex gap-6">
<a class="text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" href="#">Beranda</a>
<a class="text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" href="#">Tentang</a>
<a class="text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" href="#">Bantuan</a>
</div>
</div>
</header>
<!-- Main Content: Centered Registration Card -->
<main class="flex-grow flex items-center justify-center p-4 md:p-8">
<div class="w-full max-w-[560px] bg-white dark:bg-neutral-dark rounded-xl shadow-lg border border-neutral-light dark:border-neutral-dark overflow-hidden">
<div class="p-8">
<!-- Form Header -->
<div class="mb-8 text-center">
<h2 class="text-slate-900 dark:text-slate-100 text-3xl font-bold leading-tight">Buat Akun Baru</h2>
<p class="text-slate-500 dark:text-slate-400 mt-2">Silakan lengkapi data diri Anda untuk memulai deteksi kesehatan</p>
</div>
<!-- Registration Form -->
<form class="space-y-5">
<!-- Nama & Email -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Nama Lengkap</label>
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3" placeholder="Masukkan nama Anda" type="text"/>
</div>
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Email</label>
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3" placeholder="nama@email.com" type="email"/>
</div>
</div>
<!-- Usia & Jenis Kelamin -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Usia</label>
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3" placeholder="Contoh: 25" type="number"/>
</div>
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Jenis Kelamin</label>
<select class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3">
<option disabled="" selected="" value="">Pilih</option>
<option value="male">Laki-laki</option>
<option value="female">Perempuan</option>
</select>
</div>
</div>
<!-- Tinggi & Berat Badan -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Tinggi Badan (cm)</label>
<div class="relative">
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3 pr-12" placeholder="170" type="number"/>
<span class="absolute right-4 top-3 text-slate-400">cm</span>
</div>
</div>
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Berat Badan (kg)</label>
<div class="relative">
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3 pr-12" placeholder="65" type="number"/>
<span class="absolute right-4 top-3 text-slate-400">kg</span>
</div>
</div>
</div>
<!-- Password -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Password</label>
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3" placeholder="••••••••" type="password"/>
</div>
<div class="flex flex-col gap-2">
<label class="text-slate-700 dark:text-slate-200 text-sm font-medium">Konfirmasi Password</label>
<input class="w-full rounded-lg border-neutral-light dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-primary focus:border-primary p-3" placeholder="••••••••" type="password"/>
</div>
</div>
<!-- Submit Button -->
<div class="pt-4">
<button class="w-full bg-primary hover:bg-opacity-90 text-white font-bold py-4 rounded-lg shadow-md transition-all flex items-center justify-center gap-2" type="submit">
<span>Daftar</span>
<span class="material-symbols-outlined">person_add</span>
</button>
</div>
</form>
<!-- Footer Link -->
<div class="mt-8 text-center border-t border-neutral-light dark:border-slate-700 pt-6">
<p class="text-slate-600 dark:text-slate-400">
                        Sudah punya akun?
                        <a class="text-primary font-semibold hover:underline decoration-2 underline-offset-4" href="#">Login</a>
</p>
</div>
</div>
</div>
</main>
<!-- Simple Footer -->
<footer class="py-6 text-center text-slate-500 dark:text-slate-400 text-sm">
<p>© 2024 Obesity Detection System. Hak Cipta Dilindungi.</p>
</footer>
</body></html>
