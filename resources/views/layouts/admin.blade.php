<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Obesity Detection')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-item:hover {
            background: #f0fdf4;
            transform: translateX(4px);
        }

        .sidebar-item.active {
            background: #dcfce7;
            color: #059669;
        }
    </style>
</head>

<body class="bg-gray-100 text-slate-800">

<div class="flex min-h-screen">

    <!-- SIDEBAR (SAMA PERSIS DENGAN DASHBOARD) -->
    <aside class="w-64 bg-white border-r border-gray-200 p-6 hidden lg:flex flex-col justify-between shadow-sm sticky top-0 h-screen">
        <div>

            <!-- LOGO -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">SiObe</h1>
                    <p class="text-sm text-gray-500">Admin Dashboard</p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-house text-emerald-500 text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('users.index') }}" class="sidebar-item {{ request()->routeIs('users*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-users text-cyan-500 text-lg w-5 text-center"></i>
                    <span>Data User</span>
                </a>

                <a href="{{ route('manajemen.data') }}" class="sidebar-item {{ request()->routeIs('manajemen.data*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-database text-blue-500 text-lg"></i>
                    <span>Data Management</span>
                </a>

                <a href="{{ route('articles.index') }}" class="sidebar-item {{ request()->routeIs('articles*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-newspaper text-violet-500 text-lg"></i>
                    <span>Articles</span>
                </a>

                <a href="{{ route('kategori.index') }}" class="sidebar-item {{ request()->routeIs('kategori*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-layer-group text-pink-500 text-lg"></i>
                    <span>Categories</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl text-left font-medium">
                        <i class="fas fa-right-from-bracket text-red-500 text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

</div>

<script>
    // Active state untuk mobile (opsional)
</script>

@stack('scripts')

</body>
</html>
