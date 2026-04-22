<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Data</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
    <aside class="w-64 bg-white border-r p-6 hidden lg:flex flex-col justify-between shadow-sm sticky top-0 h-screen">

        <div>
            <!-- LOGO -->
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">SiObe</h1>
                    <p class="text-sm text-gray-500">Admin Dashboard</p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="space-y-2">

                <a href="/dashboard"
                   class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl
                   {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-house text-emerald-500"></i>
                    Dashboard
                </a>

                <a href="{{ route('manajemen.data') }}"
                   class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl
                   {{ request()->is('dashboard/manajemen-data') ? 'active' : '' }}">
                    <i class="fas fa-database text-blue-500"></i>
                    Data Management
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-newspaper text-violet-500"></i>
                    Articles
                </a>

                <a href="{{ route('kategori.index') }}"
                   class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl
                   {{ request()->is('dashboard/kategori') ? 'active' : '' }}">
                    <i class="fas fa-layer-group text-pink-500"></i>
                    Categories
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-gear text-yellow-500"></i>
                    Settings
                </a>

                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl text-left">
                        <i class="fas fa-right-from-bracket text-red-500"></i>
                        Logout
                    </button>
                </form>

            </nav>
        </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-8 w-full">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-green-600">
                    Manajemen Data Obesitas
                </h1>
                <p class="text-gray-500 mt-1">
                    Upload dan kelola data pasien obesitas
                </p>
            </div>

            <div class="hidden lg:flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                    AD
                </div>
                <div>
                    <p class="font-semibold">Admin</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <!-- UPLOAD -->
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Upload Data Excel</h2>

                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <input type="file" name="file"
                        class="w-full border p-3 rounded-xl"
                        accept=".xls,.xlsx" required>

                    <button class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600">
                        <i class="fa fa-upload"></i> Upload Data
                    </button>
                </form>
            </div>

            <!-- TABLE -->
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Data Obesitas</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-green-500 text-white">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">Usia</th>
                                <th class="p-3 text-left">Gender</th>
                                <th class="p-3 text-left">Berat</th>
                                <th class="p-3 text-left">Tinggi</th>
                                <th class="p-3 text-left">BMI</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($data ?? [] as $index => $d)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $d['nama'] }}</td>
                                <td class="p-3">{{ $d['usia'] }}</td>
                                <td class="p-3">{{ $d['gender'] }}</td>
                                <td class="p-3">{{ $d['berat'] }}</td>
                                <td class="p-3">{{ $d['tinggi'] }}</td>
                                <td class="p-3 font-semibold text-green-600">{{ $d['bmi'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-4 text-gray-500">
                                    Data belum ada
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </main>
</div>

</body>
</html>
