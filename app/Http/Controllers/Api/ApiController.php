<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BodyData;
use App\Models\AnalysisResult;
use App\Models\Workout;

class ApiController extends Controller
{
    // ==========================================
    // AUTHENTICATION
    // ==========================================

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        $token = $user->createToken('flutter_app')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Fix: ganti $2b$ → $2y$ agar Laravel bisa verifikasi bcrypt dari Node.js
        $hashedPassword = str_replace('$2b$', '$2y$', $user->password);

        if (!Hash::check($request->password, $hashedPassword)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if ($user->role !== 'user') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $token = $user->createToken('flutter_app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => (string) $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }
    public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6',
    ]);

    $user = $request->user();

    // Fix bcrypt $2b$ → $2y$ (sama seperti login)
    $hashedPassword = str_replace('$2b$', '$2y$', $user->password);

    // Verifikasi password lama
    if (!Hash::check($request->old_password, $hashedPassword)) {
        return response()->json([
            'message' => 'Password lama tidak sesuai'
        ], 400);
    }

    // Update password baru
    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'message' => 'Password berhasil diubah'
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // ==========================================
    // ANALYSIS
    // ==========================================

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
        $workouts = Workout::where('tipe', $tipeWorkout)->get();

        return response()->json([
            'message' => 'Analisis berhasil',
            'analysis' => $analysis,
            'rekomendasi_workout' => $workouts
        ]);
    }

    // ==========================================
    // CHAT AI
    // ==========================================

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Ambil API Key dari .env
        $groqApiKey = env('GROQ_API_KEY');

        // Cek apakah API Key tersedia
        if (!$groqApiKey) {
            return response()->json([
                'message' => 'GROQ_API_KEY belum dikonfigurasi'
            ], 500);
        }

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $groqApiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah asisten AI yang membantu dan menjawab dalam bahasa Indonesia.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024
            ]);

            // Jika request gagal
            if ($response->failed()) {
                return response()->json([
                    'message' => 'Gagal menghubungi Groq API',
                    'error' => $response->json()
                ], 500);
            }

            $data = $response->json();

            return response()->json([
                'reply' => $data['choices'][0]['message']['content'] ?? 'Tidak ada respon dari AI'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // PREDIKSI OBESITAS
    // ==========================================

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
