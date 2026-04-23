@extends('layouts.admin')

@section('title', 'Manajemen Data')

@section('content')

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

@endsection
