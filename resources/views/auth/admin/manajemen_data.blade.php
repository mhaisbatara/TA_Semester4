@extends('layouts.admin')

@section('title', 'Manajemen Data')

@section('content')

<!-- HEADER -->
<div class="flex flex-col lg:flex-row justify-between gap-4 mb-6">
    <div>
        <h2 class="text-4xl lg:text-5xl font-bold text-slate-800">Manajemen Data</h2>
        <p class="text-gray-500 mt-2 text-lg">
            Upload dan kelola data pasien obesitas di sini.
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

<div class="space-y-6">

    {{-- ===================== NOTIFIKASI ===================== --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-300 text-green-800 px-5 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-times-circle text-red-500 text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-2xl shadow-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== UPLOAD ===================== --}}
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <h2 class="text-xl font-semibold mb-1">Upload Data Excel</h2>
        <p class="text-gray-400 text-sm mb-4">Format yang diterima: <span class="font-medium text-gray-600">.xlsx / .xls</span> — Maks. 10MB</p>

        {{-- Form Upload --}}
        <form action="{{ route('admin.upload-data') }}"
              method="POST"
              enctype="multipart/form-data"
              id="uploadForm"
              class="space-y-4">
            @csrf

            {{-- Dropzone area --}}
            <label for="fileInput"
                   id="dropZone"
                   class="flex flex-col items-center justify-center w-full border-2 border-dashed border-green-300 rounded-2xl p-8 cursor-pointer hover:bg-green-50 transition">
                <i class="fas fa-file-excel text-green-400 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm" id="fileNameDisplay">
                    Klik atau seret file Excel ke sini
                </p>
                <input type="file"
                       name="file"
                       id="fileInput"
                       accept=".xlsx,.xls"
                       class="hidden"
                       onchange="updateFileName(this)"
                       required>
            </label>

            <div class="flex items-center gap-3">
                <button type="submit"
                        id="uploadBtn"
                        disabled
                        class="flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    <i class="fa fa-upload" id="uploadIcon"></i>
                    <span id="uploadLabel">Upload Data</span>
                </button>
            </div>
        </form>

        {{-- Form Hapus Semua Data — dipisah dari form upload agar tidak nested --}}
        @if(isset($totalData) && $totalData > 0)
        <form action="{{ route('admin.delete-all-data') }}"
              method="POST"
              id="deleteAllForm"
              class="mt-3">
            @csrf
            @method('DELETE')
            <button type="button"
                    onclick="confirmDeleteAll()"
                    class="flex items-center gap-2 bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 transition">
                <i class="fas fa-trash"></i> Hapus Semua Data
            </button>
        </form>
        @endif
    </div>

    {{-- ===================== TABEL ===================== --}}
    {{--
        Kolom yang ada di database (hasil tinker):
        usia, jenis_kelamin, tinggi_badan, berat_badan,
        konsumsi_alkohol, sering_makan_tinggi_kalori,
        frekuensi_konsumsi_sayur, jumlah_makan_harian,
        monitoring_kalori, merokok, konsumsi_air,
        riwayat_keluarga_overweight, transportasi, kategori_obesitas

        Kolom aktivitas_fisik, waktu_layar, kebiasaan_ngemil
        TIDAK tersimpan di DB — perlu fix di controller/import.
        Sementara kolom ini tetap ditampilkan dengan nilai dari DB (akan '-' jika belum ada).
    --}}
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Data Obesitas</h2>
            @if(isset($totalData))
                <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-1 rounded-full">
                    Total: {{ $totalData }} data
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-3 text-left rounded-l-xl whitespace-nowrap">No</th>
                        <th class="p-3 text-left whitespace-nowrap">Usia</th>
                        <th class="p-3 text-left whitespace-nowrap">Jenis Kelamin</th>
                        <th class="p-3 text-left whitespace-nowrap">Tinggi (cm)</th>
                        <th class="p-3 text-left whitespace-nowrap">Berat (kg)</th>
                        <th class="p-3 text-left whitespace-nowrap">Konsumsi Alkohol</th>
                        <th class="p-3 text-left whitespace-nowrap">Makan Tinggi Kalori</th>
                        <th class="p-3 text-left whitespace-nowrap">Frek. Sayur</th>
                        <th class="p-3 text-left whitespace-nowrap">Makan/Hari</th>
                        <th class="p-3 text-left whitespace-nowrap">Monitor Kalori</th>
                        <th class="p-3 text-left whitespace-nowrap">Merokok</th>
                        <th class="p-3 text-left whitespace-nowrap">Konsumsi Air</th>
                        <th class="p-3 text-left whitespace-nowrap">Riwayat Keluarga Obesitas</th>
                        <th class="p-3 text-left whitespace-nowrap">Aktivitas Fisik</th>
                        <th class="p-3 text-left whitespace-nowrap">Waktu Layar (HP)</th>
                        <th class="p-3 text-left whitespace-nowrap">Kebiasaan Ngemil</th>
                        <th class="p-3 text-left whitespace-nowrap">Transportasi</th>
                        <th class="p-3 text-left rounded-r-xl whitespace-nowrap">Kategori Obesitas</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $d)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3 text-gray-500">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}
                        </td>
                        <td class="p-3">{{ $d->usia ?? '-' }}</td>
                        <td class="p-3">{{ $d->jenis_kelamin ?? '-' }}</td>
                        <td class="p-3">{{ $d->tinggi_badan ?? '-' }}</td>
                        <td class="p-3">{{ $d->berat_badan ?? '-' }}</td>
                        <td class="p-3">{{ $d->konsumsi_alkohol ?? '-' }}</td>
                        <td class="p-3">{{ $d->sering_makan_tinggi_kalori ?? '-' }}</td>
                        <td class="p-3">{{ $d->frekuensi_konsumsi_sayur ?? '-' }}</td>
                        <td class="p-3">{{ $d->jumlah_makan_harian ?? '-' }}</td>
                        <td class="p-3">{{ $d->monitoring_kalori ?? '-' }}</td>
                        <td class="p-3">{{ $d->merokok ?? '-' }}</td>
                        <td class="p-3">{{ $d->konsumsi_air ?? '-' }}</td>
                        <td class="p-3">{{ $d->riwayat_keluarga_overweight ?? '-' }}</td>
                        <td class="p-3">{{ $d->aktivitas_fisik ?? '-' }}</td>
                        <td class="p-3">{{ $d->waktu_layar ?? '-' }}</td>
                        <td class="p-3">{{ $d->kebiasaan_ngemil ?? '-' }}</td>
                        <td class="p-3">{{ $d->transportasi ?? '-' }}</td>
                        <td class="p-3">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-lg font-semibold text-xs whitespace-nowrap">
                                {{ $d->kategori_obesitas ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="18" class="text-center py-12 text-gray-400">
                            <i class="fas fa-database text-5xl mb-3 text-gray-200 block"></i>
                            Data belum ada. Silakan upload file Excel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($data) && $data->hasPages())
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ===================== SCRIPT ===================== --}}
<script>
function updateFileName(input) {
    const display = document.getElementById('fileNameDisplay');
    const btn     = document.getElementById('uploadBtn');

    if (input.files && input.files[0]) {
        display.innerHTML = '<span class="text-green-600 font-medium">' + input.files[0].name + '</span>';
        btn.disabled = false;
    } else {
        display.textContent = 'Klik atau seret file Excel ke sini';
        btn.disabled = true;
    }
}

document.getElementById('uploadForm').addEventListener('submit', function () {
    const btn    = document.getElementById('uploadBtn');
    const label  = document.getElementById('uploadLabel');
    const icon   = document.getElementById('uploadIcon');

    btn.disabled = true;
    icon.className = 'fas fa-spinner fa-spin';
    label.textContent = 'Mengupload...';
});

function confirmDeleteAll() {
    const totalData = {{ $totalData ?? 0 }};
    if (confirm('Yakin ingin menghapus semua ' + totalData + ' data? Tindakan ini tidak dapat dibatalkan!')) {
        const form = document.getElementById('deleteAllForm');
        const btn = form.querySelector('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
        form.submit();
    }
}
</script>

@endsection
