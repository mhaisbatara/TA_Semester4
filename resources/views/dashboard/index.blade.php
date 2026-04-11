@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview & Statistics')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="glass-effect rounded-3xl p-8 border border-white/10 backdrop-blur-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Good Morning, {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-xl text-gray-300">Here's what's happening with your system today</p>
            </div>
            <div class="flex items-center space-x-3 text-sm bg-white/5 px-6 py-3 rounded-2xl border border-white/10">
                <i class="fas fa-calendar-day text-emerald-400"></i>
                <span>{{ now()->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8 hover:bg-white/10 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Total Users</p>
                    <p class="text-4xl font-bold text-white mt-2">1,247</p>
                </div>
                <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500/40 transition-all duration-300">
                    <i class="fas fa-users text-emerald-400 text-2xl group-hover:text-emerald-300"></i>
                </div>
            </div>
            <div class="mt-4 bg-gradient-to-r from-emerald-500/20 to-green-500/20 rounded-xl p-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">↑ 12% this month</span>
                    <i class="fas fa-arrow-up text-emerald-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Data -->
        <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8 hover:bg-white/10 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Total Records</p>
                <p class="text-4xl font-bold text-white mt-2">{{ count($allData ?? []) }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center group-hover:bg-blue-500/40 transition-all duration-300">
                    <i class="fas fa-database text-blue-400 text-2xl group-hover:text-blue-300"></i>
                </div>
            </div>
            <div class="mt-4 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-xl p-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">↑ 8% this week</span>
                    <i class="fas fa-arrow-up text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Activity -->
        <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8 hover:bg-white/10 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Recent Activity</p>
                    <p class="text-4xl font-bold text-white mt-2">89</p>
                </div>
                <div class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center group-hover:bg-purple-500/40 transition-all duration-300">
                    <i class="fas fa-clock text-purple-400 text-2xl group-hover:text-purple-300"></i>
                </div>
            </div>
            <div class="mt-4 bg-gradient-to-r from-purple-500/20 to-violet-500/20 rounded-xl p-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">24 today</span>
                    <i class="fas fa-arrow-up text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Reports -->
        <div class="group bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8 hover:bg-white/10 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Reports Generated</p>
                    <p class="text-4xl font-bold text-white mt-2">156</p>
                </div>
                <div class="w-16 h-16 bg-orange-500/20 rounded-2xl flex items-center justify-center group-hover:bg-orange-500/40 transition-all duration-300">
                    <i class="fas fa-file-alt text-orange-400 text-2xl group-hover:text-orange-300"></i>
                </div>
            </div>
            <div class="mt-4 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-xl p-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">↑ 15% this month</span>
                    <i class="fas fa-arrow-up text-orange-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions -->
        <div class="lg:col-span-2">
            <div class="glass-effect rounded-3xl p-8 border border-white/10 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-white">Quick Actions</h3>
                    <a href="{{ route('obesitas.create') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold text-sm flex items-center space-x-1">
                        <span>View All Data</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('obesitas.create') }}" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-emerald-500/20 rounded-2xl group-hover:bg-emerald-500/40 flex items-center justify-center">
                            <i class="fas fa-plus text-emerald-400 text-xl group-hover:text-emerald-300"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2 text-lg">Add New Data</h4>
                        <p class="text-gray-400 text-sm">Add obesity classification data</p>
                    </a>

                    <a href="#" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-500/20 rounded-2xl group-hover:bg-blue-500/40 flex items-center justify-center">
                            <i class="fas fa-users text-blue-400 text-xl group-hover:text-blue-300"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2 text-lg">Manage Users</h4>
                        <p class="text-gray-400 text-sm">View and manage user accounts</p>
                    </a>

                    <a href="#" class="group p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-purple-500/20 rounded-2xl group-hover:bg-purple-500/40 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-purple-400 text-xl group-hover:text-purple-300"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2 text-lg">Generate Report</h4>
                        <p class="text-gray-400 text-sm">Create and export analytics reports</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Data -->
        <div>
            <div class="glass-effect rounded-3xl p-8 border border-white/10 backdrop-blur-sm h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white">Recent Data</h3>
                    <a href="{{ route('obesitas.index') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-3">
@forelse($recentData ?? collect() as $item)
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-white text-sm">{{ $item['kategori'] ?? 'Unknown' }}</p>
                                <p class="text-gray-400 text-xs">{{ $item['keterangan'] ?? '' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-mono text-emerald-400 text-sm">{{ $item['bmi_min'] }} - {{ $item['bmi_max'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-database text-3xl mb-2 opacity-50"></i>
                        <p>No data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
