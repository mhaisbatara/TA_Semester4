<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - SiObe')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-item {
            transition: all 0.2s ease;
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

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 p-6 hidden lg:flex flex-col justify-between shadow-sm sticky top-0 h-screen relative z-20">
        <div>

            <!-- LOGO -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">SiObe</h1>
                    <p class="text-sm text-gray-500">Admin Dashboard</p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="space-y-2">

                <a href="{{ route('dashboard') }}"
                   class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-house text-emerald-500 text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('manajemen.data') }}"
                   class="sidebar-item {{ request()->routeIs('manajemen.data*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-database text-blue-500 text-lg"></i>
                    <span>Data Management</span>
                </a>

                <a href="{{ route('articles.index') }}"
                   class="sidebar-item {{ request()->routeIs('articles*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-newspaper text-violet-500 text-lg"></i>
                    <span>Articles</span>
                </a>

                <a href="{{ route('kategori.index') }}"
                   class="sidebar-item {{ request()->routeIs('kategori*') ? 'active' : '' }} flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-layer-group text-pink-500 text-lg"></i>
                    <span>Categories</span>
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-gear text-yellow-500 text-lg"></i>
                    <span>Settings</span>
                </a>

                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl text-left font-medium">
                        <i class="fas fa-right-from-bracket text-red-500 text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>

            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 overflow-y-auto relative z-10">
        @yield('content')
    </main>

</div>

</body>
</html>
