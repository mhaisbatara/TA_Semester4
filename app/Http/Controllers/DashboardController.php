<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $client = new Client("mongodb://127.0.0.1:27017");
            $collection = $client->db_obesitas_workout->tingkatan_obesitas;

            $allData = $collection->find()->toArray();
            $recentData = collect(array_slice($allData, 0, 5));

            return view('auth.admin.dashboard', compact('allData', 'recentData'));

        } catch (\Exception $e) {
            $recentData = collect([]);
            $allData = [];
            return view('auth.admin.dashboard', compact('allData', 'recentData'));
        }
    }
}
