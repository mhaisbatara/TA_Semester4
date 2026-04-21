@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('subtitle', 'Perbarui konten artikel Anda')

@section('content')

<style>
    .form-container { max-width: 900px; margin: auto; }
    .form-group { margin-bottom: 1.5rem; }
    label { font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block; }
    input, textarea, select {
        width: 100%; padding: 0.875rem; border: 2px solid #e5e7eb;
        border-radius: 12px; font-size: 1rem; transition: all 0.2s;
        background: white;
    }
    input:focus, textarea:focus, select:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    textarea { min-height: 120px; resize: vertical; }
    .btn-update {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white; padding: 1rem 2rem; border-radius: 12px;
        font-weight: 600; font-size: 1rem; border: none; cursor: pointer;
        box-shadow: 0 10px 25px rgba(245,158,11,0.25); transition: 0.2s;
    }
    .btn-update:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(245,158,11,0.35); }
    .tag-preview { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
    .tag-chip { background: #eff6ff; color: #2563eb; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; }
    .slug-preview { font-family: monospace; background: #f9fafb; padding: 0.5rem; border-radius: 8px; font-size: 0.875rem; color: #6b7280; }
    .current-image { margin-top: 0.5rem; padding: 0.75rem; background: #f9fafb; border-radius: 12px; display: flex; align-items: center; gap: 1rem; }
    .current-image img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
</style>

<div class="form-container">
    <!-- Header dengan Back Link -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('articles.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
        </a>
    </div>

    <!-- Form Edit -->
    <form method="POST" action="{{ route('articles.update', $article->_id ?? $article->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-md p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div>
                <div class="form-group">
                    <label>Judul Artikel *</label>
                    <input type="text" name="judul" id="judul" required
                           placeholder="Masukkan judul artikel..."
                           value="{{ old('judul', $article->judul) }}">
                    @error('judul') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" id="kategori"
                           placeholder="Contoh: Kesehatan, Obesitas, Gizi"
                           value="{{ old('kategori', $article->kategori) }}">
                </div>

                <div class="form-group">
                    <label>Penulis</label>
                    <input type="text" name="penulis" id="penulis"
                           placeholder="Nama penulis"
                           value="{{ old('penulis', $article->penulis ?? Auth::user()->name ?? 'Admin') }}">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status">
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>📄 Draft</option>
                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>🚀 Published</option>
                    </select>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="form-group">
                    <label>Slug (Auto-generated)</label>
                    <div class="slug-preview" id="slug-preview">{{ $article->slug ?? 'Masukkan judul' }}</div>
                    <input type="hidden" name="slug" id="slug" value="{{ $article->slug ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Tag (pisahkan dengan koma)</label>
                    <input type="text" id="tag-input" placeholder="obesitas,kesehatan,diet"
                           value="{{ old('tag', is_array($article->tag) ? implode(',', $article->tag) : $article->tag) }}">
                    <div class="tag-preview" id="tag-preview"></div>
                    <input type="hidden" name="tag" id="tag-hidden" value="{{ is_array($article->tag) ? json_encode($article->tag) : $article->tag }}">
                </div>

                <div class="form-group">
                    <label>Gambar Featured</label>
                    <input type="file" name="gambar" accept="image/*" class="border p-2">
                    <small class="text-gray-500 block mt-1">Upload JPG, PNG, max 5MB (kosongkan jika tidak ingin mengubah)</small>

                    @if(!empty($article->gambar))
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $article->gambar) }}" alt="Current image">
                        <div>
                            <p class="text-sm text-gray-600">Gambar saat ini</p>
                            <label class="inline-flex items-center gap-2 text-sm text-red-600">
                                <input type="checkbox" name="hapus_gambar" value="1"> Hapus gambar
                            </label>
                        </div>
                    </div>
                    @endif
                </div>

                <input type="hidden" name="views" value="{{ $article->views ?? 0 }}">
            </div>
        </div>

        <div class="form-group">
            <label>Ringkasan</label>
            <textarea name="ringkasan" placeholder="Ringkasan singkat artikel (150 karakter max)"
                      maxlength="150">{{ old('ringkasan', $article->ringkasan) }}</textarea>
        </div>

        <div class="form-group">
            <label>Isi Artikel *</label>
            <textarea name="isi" required placeholder="Tulis konten lengkap artikel Anda..."
                      style="min-height: 300px;">{{ old('isi', $article->isi) }}</textarea>
            @error('isi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 justify-end mt-8">
            <a href="{{ route('articles.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="btn-update">
                <i class="fas fa-edit"></i> Update Artikel
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
    function initTagPreview() {
        const tagInput = document.getElementById('tag-input');
        const tagValue = tagInput.value;
        if (tagValue) {
            const tags = tagValue.split(',').map(tag => tag.trim()).filter(tag => tag);
            const preview = document.getElementById('tag-preview');
            preview.innerHTML = tags.map(tag => `<span class="tag-chip">#${tag}</span>`).join('');
        }
    }

    document.getElementById('tag-input').addEventListener('input', function() {
        const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag);
        const preview = document.getElementById('tag-preview');
        const hidden = document.getElementById('tag-hidden');

        preview.innerHTML = tags.map(tag => `<span class="tag-chip">#${tag}</span>`).join('');
        hidden.value = JSON.stringify(tags);
    });

    document.addEventListener('DOMContentLoaded', function() {
        initTagPreview();
    });
</script>

@endsection
