<!DOCTYPE html>
<html>
<head>
    <title>Create Article</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .form-container { max-width: 900px; margin: auto; padding: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block; }
        input, textarea, select {
            width: 100%; padding: 0.875rem; border: 2px solid #e5e7eb;
            border-radius: 12px; font-size: 1rem; transition: all 0.2s;
            background: white;
        }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
        textarea { min-height: 120px; resize: vertical; }
        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white; padding: 1rem 2rem; border-radius: 12px;
            font-weight: 600; font-size: 1rem; border: none; cursor: pointer;
            box-shadow: 0 10px 25px rgba(16,185,129,0.25); transition: 0.2s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(16,185,129,0.35); }
        .tag-preview { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
        .tag-chip { background: #eff6ff; color: #2563eb; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; }
        .back-link { color: #6b7280; text-decoration: none; font-weight: 500; }
        .back-link:hover { color: #10b981; }
        .slug-preview { font-family: monospace; background: #f9fafb; padding: 0.5rem; border-radius: 8px; font-size: 0.875rem; color: #6b7280; }
        .header { text-align: center; margin-bottom: 2rem; }
        .header h1 { font-size: 2.25rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; }
        .header p { color: #6b7280; font-size: 1.125rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Header -->
        <div class="header">
            <h1>Tambah Artikel Baru</h1>
            <p>Buat konten berkualitas untuk audiens Anda</p>
        </div>

        <!-- Back Link -->
        <a href="{{ route('articles.index') }}" class="back-link mb-8 inline-block">
            ← Kembali ke Daftar Artikel
        </a>

        <!-- Form -->
        <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Left Column -->
                <div>
                    <!-- Judul -->
                    <div class="form-group">
                        <label>Judul Artikel *</label>
                        <input type="text" name="judul" id="judul" required
                               placeholder="Masukkan judul artikel yang menarik..."
                               value="{{ old('judul') }}">
                        @error('judul') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" name="kategori" id="kategori"
                               placeholder="Contoh: Teknologi, Bisnis, Kesehatan"
                               value="{{ old('kategori') }}">
                    </div>

                    <!-- Penulis -->
                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="penulis" id="penulis" value="Admin"
                               placeholder="Nama penulis">
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="status">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Slug Auto-generate -->
                    <div class="form-group">
                        <label>Slug (Auto-generated)</label>
                        <div class="slug-preview" id="slug-preview">Masukkan judul terlebih dahulu...</div>
                        <input type="hidden" name="slug" id="slug">
                    </div>

                    <!-- Tag -->
                    <div class="form-group">
                        <label>Tag (pisahkan dengan koma)</label>
                        <input type="text" id="tag-input" placeholder="react,nextjs,laravel,tailwind"
                               value="{{ old('tag') }}">
                        <div class="tag-preview" id="tag-preview"></div>
                        <input type="hidden" name="tag" id="tag-hidden">
                    </div>

                    <!-- Gambar -->
                    <div class="form-group">
                        <label>Gambar Featured</label>
                        <input type="file" name="gambar" accept="image/*">
                        <small class="text-gray-500">Upload JPG, PNG, max 5MB</small>
                    </div>

                    <!-- Views (Hidden) -->
                    <input type="hidden" name="views" value="0">
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="form-group">
                <label>Ringkasan</label>
                <textarea name="ringkasan" placeholder="Ringkasan singkat artikel (150 karakter max)"
                          maxlength="150">{{ old('ringkasan') }}</textarea>
            </div>

            <!-- Isi Artikel -->
            <div class="form-group">
                <label>Isi Artikel *</label>
                <textarea name="isi" required placeholder="Tulis konten lengkap artikel Anda..."
                          style="min-height: 300px;">{{ old('isi') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 justify-end mt-8">
                <a href="{{ route('articles.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    📝 Simpan Artikel
                </button>
            </div>

        </form>
    </div>

    <script>
        // Auto Slug Generation
        document.getElementById('judul').addEventListener('input', function() {
            const judul = this.value;
            const slug = judul.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');

            document.getElementById('slug').value = slug;
            document.getElementById('slug-preview').textContent = slug || 'Masukkan judul terlebih dahulu...';
        });

        // Tag Preview & Processing
        document.getElementById('tag-input').addEventListener('input', function() {
            const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
            const preview = document.getElementById('tag-preview');
            const hidden = document.getElementById('tag-hidden');

            preview.innerHTML = tags.map(tag => `<span class="tag-chip">#${tag}</span>`).join('');
            hidden.value = JSON.stringify(tags);
        });
    </script>
</body>
</html>

