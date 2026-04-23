@extends('layouts.admin')

@section('title', 'Kategori Tingkatan Obesitas')

@section('content')

<!-- HEADER -->
<div class="flex flex-col lg:flex-row justify-between gap-4 mb-6">
    <div>
        <h2 class="text-4xl lg:text-5xl font-bold text-slate-800">Kategori Obesitas</h2>
        <p class="text-gray-500 mt-2 text-lg">
            Kelola klasifikasi BMI (IMT) dan tingkatan obesitas di sini.
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-3xl px-6 py-4 shadow-sm flex items-center gap-4">
        <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" class="w-12 h-12 rounded-full">
        <div>
            <h4 class="font-bold text-lg text-slate-800">Admin</h4>
            <p class="text-gray-500 text-sm">Administrator</p>
        </div>
    </div>
</div>

<!-- TABEL KATEGORI -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-semibold">Daftar Klasifikasi BMI</h2>
        <p class="text-gray-500 text-sm mt-1">Berikut adalah daftar klasifikasi BMI yang tersedia</p>
    </div>

    <div class="overflow-x-auto p-6">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl">
                    <th class="p-3 text-left rounded-l-xl">No</th>
                    <th class="p-3 text-left">Kategori</th>
                    <th class="p-3 text-left">BMI Min</th>
                    <th class="p-3 text-left">BMI Max</th>
                    <th class="p-3 text-left">Rentang BMI</th>
                    <th class="p-3 text-left">Keterangan</th>
                    <th class="p-3 text-left rounded-r-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $index => $item)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3">{{ $index + 1 }}</td>
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($item->kategori == 'Kurus') bg-blue-100 text-blue-700
                            @elseif($item->kategori == 'Normal') bg-green-100 text-green-700
                            @elseif($item->kategori == 'Overweight') bg-yellow-100 text-yellow-700
                            @elseif($item->kategori == 'Obesitas I') bg-orange-100 text-orange-700
                            @elseif($item->kategori == 'Obesitas II') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="p-3 font-mono">{{ $item->bmi_min }}</td>
                    <td class="p-3 font-mono">{{ $item->bmi_max }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 bg-gray-100 rounded-lg text-sm">
                            {{ $item->bmi_min }} - {{ $item->bmi_max }}
                        </span>
                    </td>
                    <td class="p-3 text-gray-600">{{ $item->keterangan ?? '-' }}</td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            <a href="{{ route('kategori.edit', $item->_id) }}"
                               class="text-amber-600 hover:text-amber-700 bg-amber-50 px-3 py-1 rounded-lg text-sm flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button"
                                    onclick="openDeleteModal('{{ $item->_id }}', '{{ addslashes($item->kategori) }}')"
                                    class="text-red-600 hover:text-red-700 bg-red-50 px-3 py-1 rounded-lg text-sm flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-8 text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-3 text-gray-300 block"></i>
                        Belum ada data kategori. Silakan tambah kategori baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-slate-800 mb-2">Hapus Kategori</h3>
            <p class="text-gray-500 text-center mb-6">
                Apakah Anda yakin ingin menghapus kategori <br>
                <span id="deleteKategori" class="font-semibold text-red-600"></span>?
            </p>
            <p class="text-sm text-gray-400 text-center mb-6">
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FLOATING ACTION BUTTON - SAMA SEPERTI ARTICLES -->
<a href="{{ route('kategori.create') }}"
   class="fixed bottom-8 right-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 z-50 group">
    <i class="fas fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    <span class="absolute right-16 bg-gray-800 text-white text-sm px-3 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
        Tambah Kategori
    </span>
</a>

<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteKategori = document.getElementById('deleteKategori');

    function openDeleteModal(id, kategori) {
        deleteKategori.textContent = kategori;
        deleteForm.action = "{{ route('kategori.index') }}/" + id;
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>

@endsection
