@extends('layouts.admin')

@section('title', 'Manajemen Data')

@section('content')

<!-- HEADER - SAMA SEPERTI HALAMAN ARTICLES -->
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

    <!-- UPLOAD -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
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
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <h2 class="text-xl font-semibold mb-4">Data Obesitas</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-3 text-left rounded-l-xl">No</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Usia</th>
                        <th class="p-3 text-left">Gender</th>
                        <th class="p-3 text-left">Berat</th>
                        <th class="p-3 text-left">Tinggi</th>
                        <th class="p-3 text-left rounded-r-xl">BMI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data ?? [] as $index => $d)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $d['nama'] ?? '-' }}</td>
                        <td class="p-3">{{ $d['usia'] ?? '-' }}</td>
                        <td class="p-3">{{ $d['gender'] ?? '-' }}</td>
                        <td class="p-3">{{ $d['berat'] ?? '-' }}</td>
                        <td class="p-3">{{ $d['tinggi'] ?? '-' }}</td>
                        <td class="p-3 font-semibold text-green-600">{{ $d['bmi'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">
                            <i class="fas fa-database text-4xl mb-2 text-gray-300 block"></i>
                            Data belum ada. Silakan upload file Excel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
