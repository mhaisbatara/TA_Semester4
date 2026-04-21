@extends('layouts.admin')

@section('title', 'Articles')
@section('subtitle', 'Kelola artikel, berita, dan konten sistem')

@section('content')

<!-- HEADER -->
<div class="flex flex-col lg:flex-row justify-between gap-4 mb-6">
    <div>
        <h2 class="text-4xl lg:text-5xl font-bold text-slate-800">Articles</h2>
        <p class="text-gray-500 mt-2 text-lg">
            Kelola artikel, berita, dan konten sistem deteksi obesitas Anda di sini.
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-3xl px-6 py-4 shadow-sm flex items-center gap-4">
        <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" class="w-12 h-12 rounded-full">
        <div>
            <h4 class="font-bold text-lg text-slate-800">Admin</h4>
            <p class="text-gray-500 text-sm">Administrator</p>
        </div>
    </div>
</div>

<!-- GRID ARTICLES -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($articles as $article)
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition">
        @if(!empty($article->gambar))
            <img src="{{ asset('storage/' . $article->gambar) }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                <i class="fas fa-image text-4xl"></i>
            </div>
        @endif

        <div class="p-5">
            <h3 class="font-bold text-xl text-slate-800 mb-1">{{ $article->judul ?? '-' }}</h3>
            <p class="text-sm text-gray-500 mb-2">{{ $article->kategori ?? '-' }} • {{ $article->penulis ?? '-' }}</p>

            <span class="inline-block px-2 py-1 text-xs rounded-full {{ $article->status == 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $article->status ?? 'draft' }}
            </span>

            <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $article->ringkasan ?? '-' }}</p>

            <div class="mt-4 flex justify-between items-center">
                <span class="text-xs text-gray-400">
                    <i class="far fa-eye"></i> {{ $article->views ?? 0 }} views
                </span>
                <div class="flex gap-2">
                    <a href="{{ route('articles.edit', $article->_id ?? $article->id) }}"
                       class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center gap-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button"
                            onclick="openDeleteModal('{{ $article->_id ?? $article->id }}', '{{ addslashes($article->judul) }}')"
                            class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if(count($articles) == 0)
    <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm mt-6">
        <i class="fas fa-newspaper text-4xl mb-3 text-gray-300"></i>
        <p>Belum ada artikel. Klik tombol + di kanan bawah untuk membuat artikel pertama.</p>
    </div>
@endif

<!-- MODAL KONFIRMASI HAPUS -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-slate-800 mb-2">Hapus Artikel</h3>
            <p class="text-gray-500 text-center mb-6">
                Apakah Anda yakin ingin menghapus artikel <br>
                <span id="deleteJudul" class="font-semibold text-red-600"></span>?
            </p>
            <p class="text-sm text-gray-400 text-center mb-6">
                Tindakan ini tidak dapat dibatalkan. Semua data artikel akan hilang secara permanen.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FLOATING ACTION BUTTON -->
<a href="{{ route('articles.create') }}"
   class="fixed bottom-8 right-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-110 z-50 group">
    <i class="fas fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    <span class="absolute right-16 bg-gray-800 text-white text-sm px-3 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
        Tambah Artikel
    </span>
</a>

<script>
    // Modal Delete
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteJudul = document.getElementById('deleteJudul');

    function openDeleteModal(id, judul) {
        deleteJudul.textContent = judul;
        deleteForm.action = "{{ route('articles.destroy', '') }}/" + id;
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>

@endsection
