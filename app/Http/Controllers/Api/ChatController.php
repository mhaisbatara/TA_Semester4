<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request)
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
}
