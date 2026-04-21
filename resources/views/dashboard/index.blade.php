@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview & Statistics')

@section('content')
<div class="space-y-8 text-white">

    <!-- WELCOME -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-xl">
        <div class="flex items-center justify-between flex-wrap gap-4">

            <div>
                <h1 class="text-3xl font-bold mb-2">
                    Good Morning, {{ Auth::user()->name ?? 'Admin' }}!
                </h1>
                <p class="text-gray-300 text-lg">
                    Here's what's happening with your system today
                </p>
            </div>

            <div class="flex items-center gap-3 text-sm bg-white/5 px-6 py-3 rounded-2xl border border-white/10">
                <i class="fas fa-calendar-day text-emerald-400"></i>
                <span>{{ now()->format('d M Y') }}</span>
            </div>

        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- USERS -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl hover:scale-[1.02] transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total Users</p>
                    <p class="text-3xl font-bold">1,247</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-emerald-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- DATA -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl hover:scale-[1.02] transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Total Records</p>
                    <p class="text-3xl font-bold">{{ count($allData ?? []) }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-database text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ACTIVITY -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl hover:scale-[1.02] transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Recent Activity</p>
                    <p class="text-3xl font-bold">89</p>
                </div>
                <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-clock text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- REPORT -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl hover:scale-[1.02] transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-400 text-sm">Reports</p>
                    <p class="text-3xl font-bold">156</p>
                </div>
                <div class="w-14 h-14 bg-orange-500/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS + RECENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- QUICK ACTIONS -->
        <div class="lg:col-span-2">
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-xl">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <a href="{{ route('obesitas.create') }}"
                        class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center hover:bg-white/10 transition">
                        <i class="fas fa-plus text-emerald-400 text-2xl mb-3"></i>
                        <h4 class="font-semibold">Add Data</h4>
                    </a>

                    <a href="#"
                        class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center hover:bg-white/10 transition">
                        <i class="fas fa-users text-blue-400 text-2xl mb-3"></i>
                        <h4 class="font-semibold">Users</h4>
                    </a>

                    <a href="#"
                        class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center hover:bg-white/10 transition">
                        <i class="fas fa-chart-bar text-purple-400 text-2xl mb-3"></i>
                        <h4 class="font-semibold">Reports</h4>
                    </a>

                </div>

            </div>
        </div>

        <!-- RECENT DATA -->
        <div>
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl h-full">

                <h3 class="text-xl font-bold mb-4">Recent Data</h3>

                <div class="space-y-3">

                    @forelse($recentData ?? collect() as $item)
                        <div class="bg-white/5 border border-white/10 p-4 rounded-2xl">
                            <p class="font-semibold">{{ $item['kategori'] ?? 'Unknown' }}</p>
                            <p class="text-gray-400 text-xs">{{ $item['keterangan'] ?? '' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-6">No data available</p>
                    @endforelse

                </div>

            </div>
        </div>

    </div>

</div>
@endsection
