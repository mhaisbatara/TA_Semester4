<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Obesity Detection')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        html { height: 100%; overscroll-behavior: none; scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; height: 100%; margin: 0; }
        .sidebar-hover { @apply hover:bg-white/10 hover:text-white transition-all duration-200; }
        .glass-effect { backdrop-filter: blur(20px); background: rgba(255,255,255,0.05); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900/30 to-slate-900 min-h-screen text-white h-screen overflow-x-hidden overflow-y-auto">

    <!-- Sidebar Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden"></div>

    <div class="flex h-screen w-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gradient-to-b from-slate-900/90 to-slate-800/90 backdrop-blur-xl border-r border-white/10 fixed top-0 left-0 h-screen z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl">

            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-weight-scale text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">Obesity Admin</h2>
                        <p class="text-xs text-gray-400 font-medium">v2.0</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2 mt-6">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover {{ request()->routeIs('dashboard') ? 'bg-white/20 border-r-4 border-emerald-400 text-white' : 'text-gray-300' }}">
                            <i class="fas fa-chart-line w-5"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <!-- User Management -->
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover {{ request()->routeIs('users*') ? 'bg-white/20 border-r-4 border-blue-400 text-white' : 'text-gray-300' }}">
                            <i class="fas fa-users w-5"></i>
                            <span class="font-medium">User Management</span>
                        </a>
                    </li>

                    <!-- Data Management -->
                    <li>
                        <a href="{{ route('obesitas.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover {{ request()->routeIs('obesitas*') ? 'bg-white/20 border-r-4 border-green-400 text-white' : 'text-gray-300' }}">
                            <i class="fas fa-database w-5"></i>
                            <span class="font-medium">Data Management</span>
                        </a>
                    </li>

                    <!-- Articles -->
                    <li>
                        <a href="{{ route('articles.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover {{ request()->routeIs('articles*') ? 'bg-white/20 border-r-4 border-violet-400 text-white' : 'text-gray-300' }}">
                            <i class="fas fa-newspaper w-5"></i>
                            <span class="font-medium">Articles</span>
                        </a>
                    </li>

                    <!-- Analytics -->
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover text-gray-300">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="font-medium">Analytics</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-2xl sidebar-hover text-gray-300">
                            <i class="fas fa-cog w-5"></i>
                            <span class="font-medium">Settings</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <div class="my-6 border-t border-white/10"></div>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full p-3 rounded-2xl sidebar-hover text-red-300 hover:text-red-100 hover:bg-red-500/10">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
            <!-- Topbar -->
            <header class="bg-white/5 backdrop-blur-xl border-b border-white/10 shadow-sm sticky top-0 z-30 p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <!-- Left: Breadcrumbs / Title -->
                    <div class="flex items-center space-x-4">
                        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-white">@yield('title')</h1>
                            <p class="text-gray-400 text-sm">@yield('subtitle')</p>
                        </div>
                    </div>

                    <!-- Right: Search, Notifications, Profile -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search..." class="w-64 h-11 pl-12 pr-4 bg-white/10 border border-white/20 rounded-xl backdrop-blur-sm text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-400/50 focus:border-emerald-400/70 outline-none transition-all duration-300 text-sm">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-200">
                            <i class="fas fa-bell text-xl relative"></i>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-xs rounded-full flex items-center justify-center font-bold">3</span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button id="profile-dropdown" class="flex items-center space-x-3 p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full flex items-center justify-center font-semibold text-sm shadow-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden lg:block">
                                    <p class="font-semibold text-sm">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-400">Administrator</p>
                                </div>
                                <i class="fas fa-chevron-down text-sm transform group-hover:rotate-180 transition-transform duration-200"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profile-menu" class="absolute right-0 mt-2 w-56 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl opacity-0 invisible scale-95 origin-top-right transition-all duration-200 hidden">
                                <div class="py-2 px-4 border-b border-white/10">
                                    <p class="font-semibold text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-gray-400 text-sm">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="#" class="flex items-center space-x-3 px-4 py-3 sidebar-hover rounded-xl mx-1">
                                        <i class="fas fa-user w-4"></i>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="#" class="flex items-center space-x-3 px-4 py-3 sidebar-hover rounded-xl mx-1">
                                        <i class="fas fa-cog w-4"></i>
                                        <span>Settings</span>
                                    </a>
                                </div>
                                <div class="border-t border-white/10 pt-2">
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 sidebar-hover rounded-xl mx-1 text-red-300">
                                            <i class="fas fa-sign-out-alt w-4"></i>
                                            <span>Sign Out</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileMenuBtn?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Profile dropdown
        const profileDropdown = document.getElementById('profile-dropdown');
        const profileMenu = document.getElementById('profile-menu');

        profileDropdown?.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            profileMenu.classList.toggle('opacity-100');
            profileMenu.classList.toggle('invisible');
            profileMenu.classList.toggle('scale-100');
        });

        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!profileDropdown?.contains(e.target)) {
                profileMenu?.classList.add('hidden', 'opacity-0', 'invisible', 'scale-95');
            }
        });
    </script>
</body>
</html>
