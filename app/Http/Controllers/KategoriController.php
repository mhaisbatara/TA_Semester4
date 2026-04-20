<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;

class KategoriController extends Controller
{
    private function getCollection()
    {
        $client = new Client("mongodb://localhost:27017");
        return $client->db_obesitas_workout->kategori;
    }

    // TAMPIL DATA
    public function index()
    {
        $kategori = $this->getCollection()->find()->toArray();
        return view('auth.admin.kategori', compact('kategori'));
    }

    // TAMBAH & EDIT
    public function store(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ], [
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong'
        ]);

        $collection = $this->getCollection();

        // ✅ FORMAT OTOMATIS KAPITAL (Title Case)
        $namaKategori = ucwords(strtolower(trim($request->nama_kategori)));

        // 🔍 CEK DUPLIKAT
        $existing = $collection->findOne([
            'nama_kategori' => $namaKategori
        ]);

        // ❌ JIKA DUPLIKAT
        if ($existing && (!$request->id || (string)$existing->_id != $request->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kategori sudah ada!');
        }

        if ($request->id) {
            // ✏️ UPDATE
            $collection->updateOne(
                ['_id' => new ObjectId($request->id)],
                ['$set' => [
                    'nama_kategori' => $namaKategori
                ]]
            );
        } else {
            // ➕ INSERT
            $collection->insertOne([
                'nama_kategori' => $namaKategori
            ]);
        }

        return redirect('/dashboard/kategori')
            ->with('success', 'Data berhasil disimpan');
    }

    // EDIT
    public function edit($id)
    {
        $collection = $this->getCollection();

        $editData = $collection->findOne([
            '_id' => new ObjectId($id)
        ]);

        $kategori = $collection->find()->toArray();

        return view('auth.admin.kategori', compact('kategori', 'editData'));
    }

    // HAPUS
    public function delete($id)
    {
        $collection = $this->getCollection();

        $collection->deleteOne([
            '_id' => new ObjectId($id)
        ]);

        return redirect('/dashboard/kategori')
            ->with('success', 'Data berhasil dihapus');
    }
}