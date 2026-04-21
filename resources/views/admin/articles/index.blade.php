@extends('layouts.admin')

@section('title', 'Articles')
@section('subtitle', 'Kelola artikel, berita, dan konten sistem')

@section('content')

<style>
/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

@media (max-width: 1024px) {
    .grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .grid { grid-template-columns: 1fr; }
}

/* CARD */
.card {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    overflow: hidden;
    transition: 0.25s;
    backdrop-filter: blur(20px);
}

.card:hover {
    transform: translateY(-6px);
}

/* IMAGE */
.img {
    width: 100%;
    height: 190px;
    object-fit: cover;
    background: rgba(255,255,255,0.05);
}

/* CONTENT */
.content {
    padding: 18px;
}

.card-title {
    font-size: 18px;
    font-weight: 800;
}

/* BADGE */
.badge {
    display: inline-block;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 11px;
    border-radius: 999px;
    font-weight: 700;
}

.badge.published {
    background: rgba(16,185,129,0.2);
    color: #10b981;
}

.badge.draft {
    background: rgba(255,255,255,0.1);
    color: #ccc;
}

/* ACTION */
.actions {
    display: flex;
    gap: 6px;
}

.edit {
    font-size: 11px;
    padding: 6px 10px;
    background: rgba(245,158,11,0.2);
    color: #fbbf24;
    border-radius: 8px;
    text-decoration: none;
}

.delete {
    font-size: 11px;
    padding: 6px 10px;
    background: rgba(239,68,68,0.2);
    color: #ef4444;
    border-radius: 8px;
    border: none;
}
</style>

<div class="flex justify-end mb-6">
    <a href="{{ route('articles.create') }}"
       class="bg-emerald-500 hover:bg-emerald-600 px-4 py-2 rounded-xl font-semibold">
        + Tambah Artikel
    </a>
</div>

<div class="grid">

    @foreach($articles as $article)

    <div class="card">

        @if(!empty($article->gambar))
            <img src="{{ asset('storage/' . $article->gambar) }}" class="img">
        @else
            <div class="img"></div>
        @endif

        <div class="content">

            <div class="card-title">
                {{ $article->judul ?? '-' }}
            </div>

            <div class="text-sm text-gray-300 mt-1">
                {{ $article->kategori ?? '-' }} • {{ $article->penulis ?? '-' }}
            </div>

            <span class="badge {{ $article->status ?? 'draft' }}">
                {{ $article->status ?? 'draft' }}
            </span>

            <p class="text-sm mt-3 text-gray-300">
                {{ $article->ringkasan ?? '-' }}
            </p>

            <div class="mt-4 flex justify-between items-center">

                <span class="text-xs text-gray-400">
                    👁 {{ $article->views ?? 0 }} views
                </span>

                <div class="actions">

                    <a href="{{ route('articles.edit', $article->_id ?? $article->id) }}" class="edit">
                        Edit
                    </a>

                    <form action="{{ route('articles.destroy', $article->_id ?? $article->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete">Hapus</button>
                    </form>

                </div>

            </div>

        </div>
    </div>

    @endforeach

</div>

@endsection
