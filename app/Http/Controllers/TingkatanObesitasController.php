<?php

namespace App\Http\Controllers;

use App\Models\TingkatanObesitas;
use Illuminate\Http\Request;

class TingkatanObesitasController extends Controller
{
    public function index()
    {
        $kategoris = TingkatanObesitas::orderBy('bmi_min', 'asc')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:50',
            'bmi_min' => 'required|numeric|min:0',
            'bmi_max' => 'required|numeric|gt:bmi_min',
            'keterangan' => 'nullable|string'
        ]);

        TingkatanObesitas::create([
            'kategori' => $request->kategori,
            'bmi_min' => (float) $request->bmi_min,
            'bmi_max' => (float) $request->bmi_max,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = TingkatanObesitas::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:50',
            'bmi_min' => 'required|numeric|min:0',
            'bmi_max' => 'required|numeric|gt:bmi_min',
            'keterangan' => 'nullable|string'
        ]);

        $kategori = TingkatanObesitas::findOrFail($id);
        $kategori->update([
            'kategori' => $request->kategori,
            'bmi_min' => (float) $request->bmi_min,
            'bmi_max' => (float) $request->bmi_max,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kategori = TingkatanObesitas::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
