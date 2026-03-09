<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BodyData;
use App\Models\AnalysisResult;

class AnalisisController extends Controller
{
    public function inputData(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'berat' => 'required|numeric',
            'tinggi' => 'required|numeric',
            'umur' => 'required|numeric',
            'jenis_kelamin' => 'required',
            'aktivitas' => 'required'
        ]);

        // Simpan data tubuh
        $body = BodyData::create($request->all());

        // Hitung BMI
        $tinggiMeter = $request->tinggi / 100;
        $bmi = $request->berat / ($tinggiMeter * $tinggiMeter);

        // Tentukan kategori
        if ($bmi < 18.5) {
            $kategori = "Kurus";
        } elseif ($bmi < 25) {
            $kategori = "Normal";
        } elseif ($bmi < 30) {
            $kategori = "Overweight";
        } else {
            $kategori = "Obesitas";
        }

        // Hitung BMR (Mifflin St Jeor)
        if ($request->jenis_kelamin == "pria") {
            $bmr = (10 * $request->berat) + (6.25 * $request->tinggi) - (5 * $request->umur) + 5;
        } else {
            $bmr = (10 * $request->berat) + (6.25 * $request->tinggi) - (5 * $request->umur) - 161;
        }

        // Tentukan faktor aktivitas
        $aktivitasList = [
            "rendah" => 1.2,
            "ringan" => 1.375,
            "sedang" => 1.55,
            "berat" => 1.725
        ];

        $tdee = $bmr * $aktivitasList[$request->aktivitas];

        // Simpan hasil analisis
        $analysis = AnalysisResult::create([
            'user_id' => $request->user_id,
            'bmi' => round($bmi, 2),
            'kategori' => $kategori,
            'bmr' => round($bmr, 2),
            'tdee' => round($tdee, 2),
            'rekomendasi' => "Disarankan olahraga rutin dan atur pola makan"
        ]);

        // Tentukan tipe workout berdasarkan kategori
        if ($kategori == "Kurus") {
            $tipeWorkout = "bulking";
        } elseif ($kategori == "Normal") {
            $tipeWorkout = "maintenance";
        } else {
            $tipeWorkout = "fat_loss";
        }

        // Ambil rekomendasi workout
        $workouts = \App\Models\Workout::where('tipe', $tipeWorkout)->get();

        return response()->json([
            'message' => 'Analisis berhasil',
            'analysis' => $analysis,
            'rekomendasi_workout' => $workouts
        ]);
    }
}
