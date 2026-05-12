<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ObesityController extends Controller
{
    public function predict(Request $request)
    {
        $request->validate([
            'usia'          => 'required|numeric',
            'tinggi'        => 'required|numeric',
            'berat'         => 'required|numeric',
            'jenis_kelamin' => 'required|string',
            'alkohol'       => 'required|string',
            'kalori_tinggi' => 'required|string',
            'monitoring'    => 'required|string',
            'merokok'       => 'required|string',
            'riwayat'       => 'required|string',
            'ngamil'        => 'required|string',
            'transport'     => 'required|string',
            'sayur'         => 'required|numeric',
            'makan_harian'  => 'required|numeric',
            'konsumsi_air'  => 'required|numeric',
            'aktivitas'     => 'required|numeric',
            'waktu_layar'   => 'required|numeric',
        ]);

        $response = Http::post('http://127.0.0.1:5000/predict', $request->all());

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data'   => $response->json(),
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'ML service error',
        ], 500);
    }
}
