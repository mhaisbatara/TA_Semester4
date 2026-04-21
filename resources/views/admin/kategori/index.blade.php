<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Manajemen Kategori</h4>
        </div>

        <div class="card-body">

            <!-- FORM -->
            <form method="POST" action="{{ route('kategori.store') }}" class="row g-3 mb-4">
                @csrf

                <input type="hidden" name="id" value="{{ $editData->_id ?? '' }}">

                <div class="col-md-8">
                    <input type="text" name="nama_kategori"
                        class="form-control"
                        placeholder="Masukkan Nama Kategori"
                        value="{{ $editData->nama_kategori ?? '' }}"
                        required>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                        {{ isset($editData) ? 'Update' : 'Tambah' }}
                    </button>
                </div>
            </form>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d['nama_kategori'] }}</td>
                            <td>
                                <a href="{{ route('kategori.edit', $d['_id']) }}"
                                class="btn btn-warning btn-sm">
                                Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">Data masih kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>
