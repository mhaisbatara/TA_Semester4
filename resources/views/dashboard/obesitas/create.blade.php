<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Data Obesitas</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
  font-family: 'Inter', sans-serif;
}
</style>

</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<div class="bg-green-600 text-white p-4 flex justify-between items-center shadow">
  <h1 class="text-xl font-bold">Tambah Data Obesitas</h1>

  <a href="/dashboard" class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100">
    Kembali
  </a>
</div>

<!-- CONTENT -->
<div class="flex justify-center items-center min-h-[80vh]">

<div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">

<h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
  Form Tambah Data
</h2>

<form method="POST" action="{{ route('obesitas.store') }}">
@csrf

<!-- KATEGORI -->
<div class="mb-4">
<label class="block text-gray-600 text-sm mb-1">Kategori</label>
<input type="text" name="kategori" required
class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
placeholder="Contoh: Obesitas I">
</div>

<!-- BMI MIN -->
<div class="mb-4">
<label class="block text-gray-600 text-sm mb-1">BMI Minimum</label>
<input type="number" step="0.1" name="bmi_min" required
class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
placeholder="Contoh: 25.0">
</div>

<!-- BMI MAX -->
<div class="mb-4">
<label class="block text-gray-600 text-sm mb-1">BMI Maximum</label>
<input type="number" step="0.1" name="bmi_max" required
class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
placeholder="Contoh: 29.9">
</div>

<!-- KETERANGAN -->
<div class="mb-6">
<label class="block text-gray-600 text-sm mb-1">Keterangan</label>
<input type="text" name="keterangan" required
class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
placeholder="Contoh: Obesitas ringan">
</div>

<!-- BUTTON -->
<button type="submit"
class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold p-3 rounded-lg transition shadow-lg">
  Simpan Data
</button>

</form>

</div>

</div>

</body>
</html>
