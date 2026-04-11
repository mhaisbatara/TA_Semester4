<?php

namespace App\Http\Controllers;

use MongoDB\Client;
use Illuminate\Http\Request;

class TingkatanObesitasController extends Controller
{
    private $collection;

    public function __construct()
    {
        $client = new Client("mongodb://127.0.0.1:27017");
        $this->collection = $client->db_obesitas_workout->tingkatan_obesitas;
    }

    // READ
    public function index()
    {
        $data = $this->collection->find()->toArray();
        return view('dashboard.index', compact('data'));
    }

    // FORM CREATE
    public function create()
    {
        return view('dashboard.obesitas.create');
    }

    // STORE
    public function store(Request $request)
    {
        $this->collection->insertOne([
            'kategori' => $request->kategori,
            'bmi_min' => (float)$request->bmi_min,
            'bmi_max' => (float)$request->bmi_max,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('obesitas.index');
    }

    // FORM EDIT
    public function edit($id)
    {
        $data = $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        return view('dashboard.obesitas.edit', compact('data'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'kategori' => $request->kategori,
                'bmi_min' => (float)$request->bmi_min,
                'bmi_max' => (float)$request->bmi_max,
                'keterangan' => $request->keterangan
            ]]
        );

        return redirect()->route('obesitas.index');
    }

    // DELETE
    public function destroy($id)
    {
        $this->collection->deleteOne([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);

        return redirect()->route('obesitas.index');
    }
}
