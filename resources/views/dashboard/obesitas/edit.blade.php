<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Data Obesitas</title>

<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

<h2 class="text-xl font-bold mb-4">Edit Data Obesitas</h2>

<form method="POST" action="{{ route('obesitas.update', $data['_id']) }}">
@csrf

<div class="mb-4">
<label>Kategori</label>
<input type="text" name="kategori"
value="{{ $data['kategori'] }}"
class="w-full border p-2 rounded">
</div>

<div class="mb-4">
<label>BMI Min</label>
<input type="number" step="0.1" name="bmi_min"
value="{{ $data['bmi_min'] }}"
class="w-full border p-2 rounded">
</div>

<div class="mb-4">
<label>BMI Max</label>
<input type="number" step="0.1" name="bmi_max"
value="{{ $data['bmi_max'] }}"
class="w-full border p-2 rounded">
</div>

<div class="mb-4">
<label>Keterangan</label>
<input type="text" name="keterangan"
value="{{ $data['keterangan'] }}"
class="w-full border p-2 rounded">
</div>

<button class="bg-green-600 text-white px-4 py-2 rounded">
Update
</button>

</form>

</div>

</body>
</html>
