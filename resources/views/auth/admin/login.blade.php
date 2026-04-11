<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Obesity Detection System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen overflow-hidden">
    <!-- Background Gradient + Shapes -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900/50 to-blue-900/70">
        <!-- Decorative Shapes -->
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-400/20 to-blue-400/20 rounded-full blur-3xl float-anim"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-10 w-64 h-64 bg-white/5 rounded-3xl blur-xl float-anim" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-6 py-12 lg:px-24">
        <div class="flex w-full max-w-6xl flex-col lg:flex-row items-center gap-16 lg:gap-24">

            <!-- Left Side - Branding & Welcome -->
            <div class="w-full lg:w-1/2 text-center lg:text-left lg:max-w-md">
                <!-- Logo -->
                <div class="mb-8 flex items-center justify-center lg:justify-start gap-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                            Obesity Detection
                        </h2>
                        <p class="text-gray-400 text-sm font-medium mt-1">Admin Dashboard</p>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="space-y-6">
                    <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Welcome Back
                    </h1>
                    <div class="space-y-3">
                        <p class="text-xl text-gray-300 font-medium">
                            Access your analytics and patient management dashboard
                        </p>
                        <p class="text-gray-400 text-lg">
                            Monitor obesity trends, manage data, and generate reports.
                        </p>
                    </div>
                </div>

                <!-- Features Preview -->
                <div class="mt-12 grid grid-cols-2 gap-4 lg:grid-cols-1">
                    <div class="flex items-center space-x-3 text-left p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-bar text-emerald-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">Real-time Analytics</p>
                            <p class="text-gray-400 text-xs">Patient data insights</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 text-left p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">Patient Management</p>
                            <p class="text-gray-400 text-xs">Complete patient overview</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 lg:max-w-md">
                <div class="glass-card p-10 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500">

                    <!-- Form Header -->
                    <div class="text-center mb-10">
                        <h3 class="text-2xl lg:text-3xl font-bold text-white mb-2">
                            Sign in to your account
                        </h3>
                        <p class="text-gray-300 text-sm">Enter your credentials to access dashboard</p>
                    </div>

                    <!-- Error Alert -->
                    @if(session('error'))
                    <div class="mb-8 p-4 bg-red-500/10 border border-red-400/30 rounded-2xl backdrop-blur-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-exclamation-circle text-red-400 w-5"></i>
                            <span class="text-red-300 font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-semibold text-white/90 mb-3">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    required
                                    autocomplete="email"
                                    placeholder="admin@obesitydetection.com"
                                    class="w-full h-14 pl-12 pr-4 text-white bg-white/5 border border-white/20 rounded-2xl backdrop-blur-sm focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/70 transition-all duration-300 outline-none placeholder-gray-400 peer text-sm font-medium py-0"
                                >
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label class="block text-sm font-semibold text-white/90 mb-3">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full h-14 pl-12 pr-12 text-white bg-white/5 border border-white/20 rounded-2xl backdrop-blur-sm focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/70 transition-all duration-300 outline-none placeholder-gray-400 peer text-sm font-medium py-0"
                                >
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-white transition-colors duration-200"
                                >
                                    <i class="fas fa-eye w-4 h-4" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white/20 border-white/30 accent-emerald-500 text-sm">
                                <span class="ml-2 text-sm text-gray-300 font-medium">Remember me</span>
                            </label>
                            <a href="#" class="text-sm font-medium text-emerald-400 hover:text-emerald-300 transition-colors">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full h-14 bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-600 hover:from-emerald-600 hover:via-emerald-700 hover:to-green-700 text-white font-semibold text-lg rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center space-x-2 backdrop-blur-sm border border-emerald-400/30"
                        >
                            <i class="fas fa-arrow-right"></i>
                            <span>Sign In</span>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-8 border-t border-white/10 text-center">
                        <p class="text-xs text-gray-400">
                            © 2025 Obesity Detection System. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');

            btn.disabled = true;
            icon.className = 'fas fa-spinner fa-spin w-4 h-4';
            text.textContent = 'Signing In...';
        });

        // Focus input effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-emerald-400/50');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-emerald-400/50');
            });
        });
    </script>
</body>
</html>
