<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

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

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-scaleIn {
            animation: scaleIn 0.2s ease;
        }
    </style>
</head>

<body class="bg-gray-100 text-slate-800">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r p-6 hidden lg:flex flex-col shadow-sm sticky top-0 h-screen">
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
                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-house text-emerald-500"></i>
                    Dashboard
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-database text-blue-500"></i>
                    Data Management
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-newspaper text-violet-500"></i>
                    Articles
                </a>

                <a href="{{ route('kategori.index') }}" class="sidebar-item active flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-layer-group text-pink-500"></i>
                    Categories
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl">
                    <i class="fas fa-gear text-yellow-500"></i>
                    Settings
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full sidebar-item flex items-center gap-4 px-4 py-3 rounded-2xl text-left">
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
        <h1 class="text-3xl font-bold mb-6 text-green-600">
            Manajemen Kategori
        </h1>

        <!-- ATAS - BAWAH -->
        <div class="space-y-6 w-full">

            <!-- FORM TAMBAH -->
            <div class="bg-white p-6 rounded-2xl shadow-lg w-full">
                <h2 class="text-xl font-semibold mb-4">Tambah Kategori</h2>

                <form method="POST" action="/dashboard/kategori" class="space-y-4">
                    @csrf

                    <input type="text" name="nama_kategori"
                        value="{{ old('nama_kategori') }}"
                        placeholder="Masukkan nama kategori..."
                        class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-green-400"
                        required>

                    @error('nama_kategori')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <button <button class="w-48 bg-green-500 text-white py-3 rounded-xl">
                        + Tambah Kategori
                    </button>
                </form>
            </div>

            <!-- DATA KATEGORI -->
            <div class="bg-white p-6 rounded-2xl shadow-lg w-full">
                <h2 class="text-xl font-semibold mb-4">Data Kategori</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-green-500 text-white">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Nama Kategori</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($kategori as $index => $d)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3 font-medium">{{ $d['nama_kategori'] }}</td>
                                <td class="p-3 text-center">
                                    <button 
                                        onclick="openModal('{{ $d['_id'] }}', '{{ addslashes($d['nama_kategori']) }}')"
                                        class="bg-yellow-400 text-white px-4 py-2 rounded-lg hover:bg-yellow-500">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center p-4 text-gray-500">
                                    Data kosong
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

<!-- MODAL -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-2xl w-96 shadow-xl animate-scaleIn">
        <h2 class="text-lg font-bold mb-4">Edit Kategori</h2>

        <form method="POST" action="/dashboard/kategori">
            @csrf

            <input type="hidden" name="id" id="edit_id">

            <input type="text" name="nama_kategori" id="edit_nama"
                class="w-full border p-3 mb-3 rounded-xl focus:ring-2 focus:ring-green-400"
                required>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" 
                    class="bg-gray-300 px-4 py-2 rounded-lg">
                    Batal
                </button>

                <button type="submit" 
                    class="bg-green-500 text-white px-4 py-2 rounded-lg">
                    Edit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->
<script>
function openModal(id, nama) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>

</body>
</html>