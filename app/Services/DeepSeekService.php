<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    public function chat(string $message): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY')
        ])->post(env('DEEPSEEK_API_URL'), [
            'model' => 'deepseek-chat',
            'messages' => [[
                'role' => 'user',
                'content' => $message
            ]],
            'temperature' => 0.7
        ]);

        return $response->json();
    }
    public function sendPdfToApi($filePath)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
    ])->attach( // Anexa o PDF
        'file',          // Nome do campo (verifique na documentação da API)
        fopen($filePath, 'r'),
        'documento.pdf'  // Nome do arquivo
    )->post('https://api.deepseek.com/v1/files/upload'); // Endpoint fictício (verifique o real)

    return $response->json();
}
}
