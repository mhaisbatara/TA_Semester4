<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
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

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<div class="w-64 bg-white h-screen shadow-md p-5">
    <h2 class="text-xl font-bold mb-6 text-green-600">Obesity Detection</h2>

    <ul class="space-y-3">
        <li>
            <a href="/dashboard" class="block p-2 hover:bg-green-100 rounded">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/dashboard/kategori" class="block p-2 bg-green-200 rounded font-semibold">
                Kategori
            </a>
        </li>
    </ul>
</div>

<!-- CONTENT -->
<div class="flex-1 p-8 bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-6 text-green-600">
     Kategori</h1>
    </div>
    <!-- FORM TAMBAH -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-6">
        <h2 class="text-xl font-semibold mb-4">Tambah Kategori</h2>

        <form method="POST" action="/dashboard/kategori">
            @csrf

            <div class="flex gap-3">
                <input type="text" name="nama_kategori"
                    value="{{ old('nama_kategori') }}"
                    placeholder="Masukkan nama kategori..."
                    class="flex-1 border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                    required>

                <button class="bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600 transition shadow">
                    + Tambah
                </button>
            </div>

            <!-- ERROR TAMBAH -->
            @error('nama_kategori')
                <div class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </div>
            @enderror
        </form>
    </div>


    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Data Kategori</h2>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Kategori</th>
                        <th class="p-3 text-center"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($kategori as $index => $d)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3 font-medium">{{ $d['nama_kategori'] }}</td>
                        <td class="p-3 text-center">
                            <button 
                                onclick="openModal('{{ $d['_id'] }}', '{{ addslashes($d['nama_kategori']) }}')"
                                class="bg-yellow-400 text-white px-4 py-1.5 rounded-lg hover:bg-yellow-500 transition shadow">
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

<!-- MODAL -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-2xl w-96 shadow-xl animate-scaleIn">
        <h2 class="text-lg font-bold mb-4">Edit Kategori</h2>

        <form method="POST" action="/dashboard/kategori">
            @csrf

            <input type="hidden" name="id" id="edit_id">

            <input type="text" name="nama_kategori" id="edit_nama"
                value="{{ old('nama_kategori') }}"
                class="w-full border border-gray-300 p-2 mb-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                required>

            <!-- ERROR EDIT -->
            @error('nama_kategori')
                <div class="text-red-500 text-sm mb-2">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" 
                    class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </button>

                <button type="submit" 
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
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