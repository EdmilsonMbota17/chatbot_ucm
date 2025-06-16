<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use App\Models\Document;

class ChatController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate(['message' => 'required|string']);
            $user_message = $request->message;
            $usuario_id = session('usuario_id');

            // Usa todo o conteúdo dos documentos como contexto
            $documentos = Document::all();
            $contexto = $documentos->pluck('text_content')->implode("\n\n");

            $response = $this->deepSeekService->chat("Com base nesse conteúdo: \n$contexto\n\nPergunta: $user_message");
            $aiResponse = $response['choices'][0]['message']['content'] ?? 'Sem resposta';

            $conversation = new Conversation();
            $conversation->usuario_id = $usuario_id;
            $conversation->user_message = $user_message;
            $conversation->ai_response = $aiResponse;
            $conversation->save();

            return response()->json([
                'data' => ['status' => 'success', 'message' => 'Operação realizada com sucesso'],
                'response' => $aiResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => ['status' => 'error', 'message' => $e->getMessage()],
            ], 500);
        }
    }
}
