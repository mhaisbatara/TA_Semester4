<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller; // ← TAMBAHKAN INI

class ObesityController extends Controller
{
    public function predict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usia'             => 'required|numeric',
            'tinggi'           => 'required|numeric',
            'berat'            => 'required|numeric',
            'jenis_kelamin'    => 'required|string',
            'alkohol'          => 'required|string',
            'kalori_tinggi'    => 'required|string',
            'monitoring'       => 'required|string',
            'merokok'          => 'required|string',
            'riwayat_keluarga' => 'required|string',
            'ngemil'           => 'required|string',
            'transportasi'     => 'required|string',
            'konsumsi_sayur'   => 'required|numeric',
            'makan_harian'     => 'required|integer',
            'konsumsi_air'     => 'required|numeric',
            'aktivitas_fisik'  => 'required|numeric',
            'waktu_layar'      => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $response = Http::timeout(30)->post(
                'http://127.0.0.1:5000/predict',
                $request->all()
            );

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ML service error',
                ], 500);
            }

            $mlData = $response->json();

            return response()->json([
                'success' => true,
                'data'    => $mlData['data'] ?? $mlData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi ML service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function save(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'prediksi'                        => 'required|array',
            'prediksi.input'                  => 'required|array',
            'prediksi.hasil'                  => 'required|array',
            'prediksi.hasil.kategori'         => 'required|string',
            'prediksi.hasil.bmi'              => 'required|numeric',
            'prediksi.hasil.confidence'       => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak terautentikasi.',
                ], 401);
            }

            $record = [
                'input'       => $request->input('prediksi.input'),
                'hasil'       => $request->input('prediksi.hasil'),
                'prediksi_at' => Carbon::now()->toIso8601String(),
            ];

            $user->pushPrediksi($record);

            return response()->json([
                'success' => true,
                'message' => 'Prediksi berhasil disimpan',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak terautentikasi.',
                ], 401);
            }

            $riwayat = collect($user->riwayat_prediksi ?? [])
                ->sortByDesc('prediksi_at')
                ->take(20)
                ->values();

            return response()->json([
                'success' => true,
                'data'    => $riwayat,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
