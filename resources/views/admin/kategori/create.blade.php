@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('kategori.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Tambah Kategori Baru</h2>
        </div>

        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Contoh: Normal, Kurus, Overweight, dll" required>
                @error('kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">BMI Minimum</label>
                    <input type="number" step="0.1" name="bmi_min" value="{{ old('bmi_min') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"
                           placeholder="0" required>
                    @error('bmi_min')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">BMI Maksimum</label>
                    <input type="number" step="0.1" name="bmi_max" value="{{ old('bmi_max') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"
                           placeholder="18.5" required>
                    @error('bmi_max')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500"
                          placeholder="Deskripsi singkat tentang kategori ini...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium transition">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
                <a href="{{ route('kategori.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
