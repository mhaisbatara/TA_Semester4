@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- HEADER -->
<div class="flex flex-col lg:flex-row justify-between gap-4 mb-6">
    <div>
        <h2 class="text-4xl lg:text-5xl font-bold text-slate-800">Dashboard</h2>
        <p class="text-gray-500 mt-2 text-lg">
            Selamat datang kembali, pantau sistem deteksi obesitas Anda di sini.
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-3xl px-6 py-4 shadow-sm flex items-center gap-4">
        <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff"
             class="w-12 h-12 rounded-full">
        <div>
            <h4 class="font-bold text-lg text-slate-800">Admin</h4>
            <p class="text-gray-500 text-sm">Administrator</p>
        </div>
    </div>
</div>

<!-- STAT -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-gray-500 text-sm">Total Pasien</h3>
        <p class="text-3xl font-bold mt-3">1,248</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-gray-500 text-sm">Pasien Sehat</h3>
        <p class="text-3xl font-bold mt-3 text-emerald-600">856</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-gray-500 text-sm">Kasus Obesitas</h3>
        <p class="text-3xl font-bold mt-3 text-red-500">392</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-gray-500 text-sm">Laporan Dibuat</h3>
        <p class="text-3xl font-bold mt-3">178</p>
    </div>
</div>

<!-- CHART -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

    <!-- LINE -->
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-xl font-bold mb-4">Kasus Obesitas Bulanan</h3>
        <div class="h-[280px]">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <!-- PIE -->
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h3 class="text-xl font-bold mb-4">Kategori Pasien</h3>
        <div class="h-[240px] max-w-[400px] mx-auto">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
// LINE CHART
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
        datasets: [{
            label: 'Kasus Obesitas',
            data: [120,150,180,170,210,250],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16,185,129,0.2)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// PIE CHART
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Healthy', 'Overweight', 'Obesity'],
        datasets: [{
            data: [856,250,392],
            backgroundColor: ['#10b981','#f59e0b','#ef4444']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                align: 'center'
            }
        }
    }
});
</script>
@endpush
