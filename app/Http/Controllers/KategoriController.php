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

    // ✅ TAMPIL DATA + SORTING BMI
    public function index()
    {
        $data = $this->getCollection()->find()->toArray();

        // 🔥 SORTING NATURAL BMI
        usort($data, function ($a, $b) {

            $a_min = $a['bmi_min'] ?? null;
            $b_min = $b['bmi_min'] ?? null;

            $a_max = $a['bmi_max'] ?? null;
            $b_max = $b['bmi_max'] ?? null;

            // ✅ CASE 1: < (min null) → paling atas
            if ($a_min === null && $b_min !== null) return -1;
            if ($a_min !== null && $b_min === null) return 1;

            // ✅ CASE 2: > (max null) → paling bawah
            if ($a_max === null && $b_max !== null) return 1;
            if ($a_max !== null && $b_max === null) return -1;

            // ✅ CASE 3: range normal → urut dari kecil ke besar
            return ($a_min <=> $b_min);
        });

        return view('auth.admin.kategori', ['kategori' => $data]);
    }

    // ✅ TAMBAH & EDIT
    public function store(Request $request)
    {
        // ✅ VALIDASI DASAR
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'bmi_min' => 'nullable|numeric',
            'bmi_max' => 'nullable|numeric'
        ], [
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong'
        ]);

        $collection = $this->getCollection();

        // ✅ FORMAT NAMA
        $namaKategori = ucwords(strtolower(trim($request->nama_kategori)));

        // 🔍 CEK DUPLIKAT
        $existing = $collection->findOne([
            'nama_kategori' => $namaKategori
            ]);

            if ($existing && (!$request->id || (string)$existing->_id != $request->id)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Nama kategori sudah ada!');
            }

        // 🔥 VALIDASI LOGIKA BMI
        if ($request->bmi_min && $request->bmi_max) {
            if ($request->bmi_max <= $request->bmi_min) {
                return back()->withInput()->with('error', 'BMI Max harus lebih besar dari BMI Min');
            }
        }

        if (!$request->bmi_min && !$request->bmi_max) {
            return back()->withInput()->with('error', 'Minimal salah satu BMI harus diisi');
        }

        // ✅ DATA
        $data = [
            'nama_kategori' => $namaKategori,
            'bmi_min' => $request->bmi_min !== null && $request->bmi_min !== ''
                ? (float)$request->bmi_min
                : null,
            'bmi_max' => $request->bmi_max !== null && $request->bmi_max !== ''
                ? (float)$request->bmi_max
                : null
        ];

        if ($request->id) {
            // ✏️ UPDATE
            $collection->updateOne(
                ['_id' => new ObjectId($request->id)],
                ['$set' => $data]
            );
        } else {
            // ➕ INSERT
            $collection->insertOne($data);
        }

        return redirect('/dashboard/kategori')
            ->with('success', 'Data berhasil disimpan');
    }

    // ✅ EDIT
    public function edit($id)
    {
        $collection = $this->getCollection();

        $editData = $collection->findOne([
            '_id' => new ObjectId($id)
        ]);

        $kategori = $collection->find()->toArray();

        return view('auth.admin.kategori', compact('kategori', 'editData'));
    }

    // ✅ HAPUS
    public function delete($id)
    {
        $this->getCollection()->deleteOne([
            '_id' => new ObjectId($id)
        ]);

        return redirect('/dashboard/kategori')
            ->with('success', 'Data berhasil dihapus');
    }
}