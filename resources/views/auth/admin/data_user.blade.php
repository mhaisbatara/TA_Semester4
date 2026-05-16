@extends('layouts.admin')

@section('title', 'Data User')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Data User</h1>
        <p class="text-gray-500 mt-1">Kelola semua pengguna yang terdaftar di sistem.</p>
    </div>
    <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white font-bold text-sm">
            AD
        </div>
        <div>
            <p class="font-semibold text-slate-800">Admin</p>
            <p class="text-xs text-gray-400">Administrator</p>
        </div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
            <i class="fas fa-users text-emerald-500 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalUsers }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
            <i class="fas fa-user-shield text-emerald-500 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Admin</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalAdmin }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center">
            <i class="fas fa-user text-green-500 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Member</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalMember }}</p>
        </div>
    </div>

</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- TABLE HEADER -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-slate-800">Daftar User</h2>
        <form method="GET" action="{{ route('users.index') }}">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau email..."
                    class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-64"
                >
            </div>
        </form>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="mx-6 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
            <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- TABLE CONTENT -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-emerald-500 text-white">
                    <th class="px-6 py-4 text-left font-semibold">No</th>
                    <th class="px-6 py-4 text-left font-semibold">Nama</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Riwayat Prediksi</th>
                    <th class="px-6 py-4 text-left font-semibold">Terdaftar</th>
                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-500">{{ $users->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ isset($user->riwayat_prediksi) ? count($user->riwayat_prediksi) : 0 }} prediksi
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('users.destroy', $user->_id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl text-xs font-medium transition-colors">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <i class="fas fa-users text-4xl mb-3 block"></i>
                        Tidak ada user ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
        </p>
        <div class="flex gap-2">
            @if($users->onFirstPage())
                <span class="px-4 py-2 rounded-xl text-sm text-gray-300 bg-gray-50 cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 rounded-xl text-sm text-gray-600 bg-gray-100 hover:bg-emerald-100 hover:text-emerald-700 transition-colors">← Prev</a>
            @endif

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 rounded-xl text-sm text-white bg-emerald-500 hover:bg-emerald-600 transition-colors">Next →</a>
            @else
                <span class="px-4 py-2 rounded-xl text-sm text-gray-300 bg-gray-50 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
    @endif

</div>

@endsection
