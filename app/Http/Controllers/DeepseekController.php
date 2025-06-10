<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepseekController extends Controller
{
    private const API_URL = 'https://api.deepseek.com/v1/chat/completions';
    private const TIMEOUT_SECONDS = 30;

    public function askDeepSeek(string $userMessage): array
    {
        if (empty($userMessage)) {
            return ['error' => 'A mensagem não pode estar vazia.'];
        }

        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post(self::API_URL, [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $apiResponse = $response->json();

                $answer = $apiResponse['choices'][0]['message']['content'] ?? 'Sem resposta';

                return [
                    'success' => true,
                    'answer' => $answer,
                    'usage' => $apiResponse['usage'], // tokens consumidos
                    'full_response' => $apiResponse
                ];
            }

            Log::error('Erro na API Deepseek', ['response' => $response->body()]);
            return [
                'error' => 'Erro na API',
                'details' => $response->json() ?? $response->body()
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Timeout Deepseek: ' . $e->getMessage());
            return [
                'error' => 'Timeout',
                'solution' => 'Tente novamente com uma mensagem mais curta'
            ];

        } catch (\Exception $e) {
            Log::error('Erro inesperado Deepseek: ' . $e->getMessage());
            return [
                'error' => 'Erro interno',
                'details' => $e->getMessage()
            ];
        }
    }
}

