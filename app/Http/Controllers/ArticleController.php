<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $articles = Article::all();

    // Fallback for empty MongoDB collection
    if ($articles->isEmpty()) {
        $articles = collect([]);
    }

   return view('admin.articles.index', compact('articles'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
$request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
            'ringkasan' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        // Handle image upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('articles', 'public');
        }

        Article::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'penulis' => auth()->user()->name ?? 'Admin',
            'ringkasan' => $request->ringkasan,
            'tag' => $request->tag, // Already JSON from form JS
            'status' => $request->status ?? 'draft',
            'isi' => $request->isi,
            'gambar' => $gambarPath,
            'slug' => $request->slug ?? Str::slug($request->judul),
            'views' => 0,
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::findOrFail($id);

        // optional: tambah views (MongoDB)
        $article->inc('views'); // atomic increment

        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);

        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $article->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'ringkasan' => $request->ringkasan,
            'tag' => $request->tag ? json_encode(explode(',', $request->tag)) : null,
            'status' => $request->status,
            'isi' => $request->isi,
            'slug' => Str::slug($request->judul),
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Article::destroy($id);

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil dihapus');
    }
}
