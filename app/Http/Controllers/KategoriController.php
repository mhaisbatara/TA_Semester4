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

    public function index()
    {
        $kategori = $this->getCollection()->find()->toArray();
        return view('auth.admin.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $collection = $this->getCollection();

        if ($request->id) {
            // UPDATE
            $collection->updateOne(
                ['_id' => new ObjectId($request->id)],
                ['$set' => ['nama_kategori' => $request->nama_kategori]]
            );
        } else {
            // INSERT
            $collection->insertOne([
                'nama_kategori' => $request->nama_kategori
            ]);
        }

        return redirect('/dashboard/kategori');
    }

    public function edit($id)
    {
        $collection = $this->getCollection();

        $editData = $collection->findOne([
            '_id' => new ObjectId($id)
        ]);

        $kategori = $collection->find()->toArray();

        return view('auth.admin.kategori', compact('kategori', 'editData'));
    }
}