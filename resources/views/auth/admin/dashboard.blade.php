<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Obesity Detection System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar-item.active {
            background: linear-gradient(to right, rgba(16, 185, 129, 0.25), rgba(34, 197, 94, 0.15));
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen overflow-x-hidden">

    <div class="flex min-h-screen">

        <aside class="w-72 glass-card border-r border-white/10 p-6 hidden lg:flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Obesity Detection</h1>
                        <p class="text-sm text-gray-400">Admin Dashboard</p>
                    </div>
                </div>

                <nav class="space-y-3">
                    <a href="#" class="sidebar-item active flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300">
                        <i class="fas fa-house text-emerald-400"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300">
                        <i class="fas fa-database text-blue-400"></i>
                        <span class="font-medium">Manajemen Data</span>
                    </a>

                    <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300">
                        <i class="fas fa-newspaper text-purple-400"></i>
                        <span class="font-medium">Input Artikel</span>
                    </a>

                    <a href="{{ route('kategori.index') }}" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300">
                        <i class="fas fa-layer-group text-pink-400"></i>
                        <span class="font-medium">Kategori</span>
                    </a>

                    <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300">
                        <i class="fas fa-gear text-yellow-400"></i>
                        <span class="font-medium">Settings</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 text-left">
                            <i class="fas fa-right-from-bracket text-red-400"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </nav>
            </div>

            <div class="glass-card rounded-3xl p-5 mt-10">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" class="w-12 h-12 rounded-full" alt="Admin">
                    <div>
                        <h4 class="font-semibold">Admin</h4>
                        <p class="text-sm text-gray-400">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-6 lg:p-10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-4xl font-bold">Dashboard Overview</h2>
                    <p class="text-gray-400 mt-2">Welcome back, monitor your obesity detection system here.</p>
                </div>

                </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                <div class="glass-card rounded-3xl p-6">
                    <h3 class="text-gray-400 text-sm">Total Patients</h3>
                    <p class="text-3xl font-bold mt-2">1,248</p>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h3 class="text-gray-400 text-sm">Healthy Patients</h3>
                    <p class="text-3xl font-bold mt-2">856</p>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h3 class="text-gray-400 text-sm">Obesity Cases</h3>
                    <p class="text-3xl font-bold mt-2">392</p>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h3 class="text-gray-400 text-sm">Reports Generated</h3>
                    <p class="text-3xl font-bold mt-2">178</p>
                </div>
            </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-10">
                <div class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold">Monthly Obesity Cases</h3>
                        <span class="text-sm text-gray-400">2025</span>
                    </div>
                    <canvas id="lineChart" class="w-full h-80"></canvas>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold">Patient Categories</h3>
                        <span class="text-sm text-gray-400">Current Data</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <canvas id="pieChart" class="w-full max-w-sm h-80"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const lineCtx = document.getElementById('lineChart');

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Obesity Cases',
                    data: [120, 150, 180, 170, 210, 250],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#9ca3af'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#9ca3af'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                }
            }
        });

        const pieCtx = document.getElementById('pieChart');

        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Healthy', 'Overweight', 'Obesity'],
                datasets: [{
                    data: [856, 250, 392],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            padding: 20
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>