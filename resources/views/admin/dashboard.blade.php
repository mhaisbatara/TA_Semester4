<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SiObe</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 p-6 hidden lg:flex flex-col justify-between shadow-sm sticky top-0 h-screen">
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
                <a href="#" class="sidebar-item active flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-house text-emerald-500 text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-database text-blue-500 text-lg"></i>
                    <span>Data Management</span>
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-newspaper text-violet-500 text-lg"></i>
                    <span>Articles</span>
                </a>

                <a href="{{ route('kategori.index') }}" class="sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-layer-group text-pink-500 text-lg"></i>
                    <span>Categories</span>
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl font-medium">
                    <i class="fas fa-gear text-yellow-500 text-lg"></i>
                    <span>Settings</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full sidebar-item flex items-center gap-4 px-4 py-4 rounded-2xl text-left font-medium">
                        <i class="fas fa-right-from-bracket text-red-500 text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-6 overflow-y-auto">

        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between gap-4 mb-6">
            <div>
                <h2 class="text-4xl lg:text-5xl font-bold text-slate-800">Dashboard</h2>
                <p class="text-gray-500 mt-2 text-lg">
                    Selamat datang kembali, pantau sistem deteksi obesitas Anda di sini.
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl px-6 py-4 shadow-sm flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff"
                     class="w-12 h-12 rounded-full">
                <div>
                    <h4 class="font-bold text-lg text-slate-800">Admin</h4>
                    <p class="text-gray-500 text-sm">Administrator</p>
                </div>
            </div>
        </div>

        <!-- STAT -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-gray-500 text-sm">Total Pasien</h3>
                <p class="text-3xl font-bold mt-3">1,248</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-gray-500 text-sm">Pasien Sehat</h3>
                <p class="text-3xl font-bold mt-3 text-emerald-600">856</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-gray-500 text-sm">Kasus Obesitas</h3>
                <p class="text-3xl font-bold mt-3 text-red-500">392</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-gray-500 text-sm">Laporan Dibuat</h3>
                <p class="text-3xl font-bold mt-3">178</p>
            </div>
        </div>

        <!-- CHART -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

            <!-- LINE -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-xl font-bold mb-4">Kasus Obesitas Bulanan</h3>
                <div class="h-[280px]">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <!-- PIE -->
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <h3 class="text-xl font-bold mb-4">Kategori Pasien</h3>

                <!-- ukuran cukup lebar biar legend 1 baris -->
                <div class="h-[240px] max-w-[400px] mx-auto">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
// LINE CHART
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
        datasets: [{
            label: 'Kasus Obesitas',
            data: [120,150,180,170,210,250],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16,185,129,0.2)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// PIE CHART (LEGEND 1 BARIS)
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Healthy', 'Overweight', 'Obesity'],
        datasets: [{
            data: [856,250,392],
            backgroundColor: ['#10b981','#f59e0b','#ef4444']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                align: 'center',
                labels: {
                    boxWidth: 20,
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>
