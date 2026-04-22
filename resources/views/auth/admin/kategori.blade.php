<!DOCTYPE html>
<html lang="id">
<head>
            <meta charset="UTF-8">
            <title>Kategori</title>
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
                </style>
            </head>

<body class="bg-gray-100 text-slate-800">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r p-6 hidden lg:flex flex-col justify-between shadow-sm sticky top-0 h-screen">
        <div>

            <!-- LOGO -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">SiObe</h1>
                    <p class="text-sm text-gray-500">Admin Panel</p>
                </div>
            </div>

            <!-- MENU -->
            <nav class="space-y-2">
                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl">
                    <i class="fas fa-house text-emerald-500"></i>
                    Dashboard
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl">
                    <i class="fas fa-database text-blue-500"></i>
                    Data Management
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl">
                    <i class="fas fa-newspaper text-violet-500"></i>
                    Articles
                </a>

                <a href="{{ route('kategori.index') }}" class="sidebar-item active flex items-center gap-4 px-4 py-3 rounded-xl">
                    <i class="fas fa-layer-group text-pink-500"></i>
                    Categories
                </a>

                <a href="#" class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl">
                    <i class="fas fa-gear text-yellow-500"></i>
                    Settings
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl text-left">
                        <i class="fas fa-right-from-bracket text-red-500"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">

            <h1 class="text-3xl font-bold mb-6 text-green-600">
            Manajemen Kategori
            </h1>
        <!-- FORM -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-6">
            <h2 class="text-lg font-semibold mb-4">Tambah Kategori</h2>

            <form method="POST" action="/dashboard/kategori" class="space-y-4">
                @csrf

                <input type="text" name="nama_kategori"
                    placeholder="Masukkan Nama kategori.."
                    class="w-full border p-3 rounded-xl" required>

                <div class="flex gap-4">
                <input type="number" step="0.1" name="bmi_min"
                        placeholder="BMI Min"
                        class="w-1/2 border p-3 rounded-xl">

                <input type="number" step="0.1" name="bmi_max"
                        placeholder="BMI Max"
                        class="w-1/2 border p-3 rounded-xl">
                </div>

                <p class="text-sm text-gray-500 mt-2">
                </p>

                <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl">
                    + Tambah
                </button>
            </form>
        </div>

        <!-- TABEL -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Data Kategori</h2>

            <table class="w-full">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-center">BMI</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategori as $i => $d)
                    <tr class="border-b hover:bg-green-50">
                        <td class="p-4">{{ $i+1 }}</td>
                        <td class="p-4">{{ $d['nama_kategori'] }}</td>

                        <td class="p-4 text-center">
                        
                        @if(empty($d['bmi_min']) && !empty($d['bmi_max']))
                            < {{ $d['bmi_max'] }}
                        @elseif(!empty($d['bmi_min']) && empty($d['bmi_max']))
                            > {{ $d['bmi_min'] }}
                        @elseif(!empty($d['bmi_min']) && !empty($d['bmi_max']))
                            {{ $d['bmi_min'] }} - {{ $d['bmi_max'] }}
                        @else
                            -
                        @endif
                        </td>

                        <td class="p-4 text-center">
                            <button 
                                onclick="openModal('{{ $d['_id'] }}','{{ $d['nama_kategori'] }}','{{ $d['bmi_min'] ?? '' }}','{{ $d['bmi_max'] ?? '' }}')"
                                class="bg-yellow-400 px-3 py-1 rounded text-white">
                                Edit
                            </button>

                           
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-4">Data kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            </main>
        </div>

<!-- MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-80">

        <form method="POST" action="/dashboard/kategori">
            @csrf

            <input type="hidden" name="id" id="id">

            <input type="text" name="nama_kategori" id="nama" class="w-full border p-2 mb-2">

            <input type="number" step="0.1" name="bmi_min" id="min" class="w-full border p-2 mb-2">

            <input type="number" step="0.1" name="bmi_max" id="max" class="w-full border p-2 mb-2">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()">Batal</button>
                <button class="bg-green-500 text-white px-3 py-1 rounded">Simpan</button>
            </div>

        </form>
    </div>
</div>


<script>
function openModal(id,nama,min,max){
    document.getElementById('id').value=id;
    document.getElementById('nama').value=nama;
    document.getElementById('min').value=min;
    document.getElementById('max').value=max;
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal(){
    document.getElementById('modal').classList.add('hidden');
}
</script>

</body>
</html>