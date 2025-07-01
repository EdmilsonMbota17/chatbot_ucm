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

    public function index(){

        if (!session()->has('usuario_id')) {
            return redirect('/')->with('error', 'Você precisa estar logado como usuário.');
        }
        else{
            return view('conversa.index');

        }


    }


    public function sendMessage(Request $request)
    {
        try {
            $request->validate(['message' => 'required|string']);
            $user_message = $request->message;
            $usuario_id = session('usuario_id');

            // Busca conteúdos dos documentos (ajuste o filtro se quiser limitar ainda mais)
            $documentos = Document::all();
            $contexto = $documentos->pluck('text_content')->implode("\n\n");

            // Monta prompt com restrição explícita para a IA
            $prompt = "Você é um assistente da Universidade Católica de Moçambique (UCM) amigavel. Responda somente com base nas informações abaixo.
Caso a pergunta do usuário não esteja claramente respondida nas informações fornecidas, responda educadamente que a informação não está disponível.

    Conteúdo disponível:
    $contexto

    Com base nisso, responda à pergunta:
    $user_message

    Se a resposta não estiver no conteúdo fornecido, responda: 'Desculpe, não tenho essa informação no momento.'";

            // Chama o serviço DeepSeek com o prompt
            $response = $this->deepSeekService->chat($prompt);

            // Pega a resposta da IA no formato esperado
            $rawResponse = $response['choices'][0]['message']['content'] ?? 'Sem resposta';

            // Formata a resposta para exibir melhor
            $aiResponse = $this->formatarResposta($rawResponse);

            // Salva no histórico da conversa
            $conversation = new Conversation();
            $conversation->usuario_id = $usuario_id;
            $conversation->user_message = $user_message;
            $conversation->ai_response = $aiResponse;
            $conversation->save();

            // Retorna resposta para frontend
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
    private function formatarResposta($texto)
    {
        // Remove negrito Markdown
        $texto = preg_replace('/\*\*(.*?)\*\*/', '$1', $texto);

        // Remove títulos markdown
        $texto = preg_replace('/#+\s*(.*?)\n/', "$1\n", $texto);

        // Remove \text{} do LaTeX
        $texto = preg_replace('/\\\\text\{(.*?)\}/', '$1', $texto);

        // Remove símbolos LaTeX \( \)
        $texto = preg_replace('/\\\\[\(\[\{](.*?)\\\\[\)\]\}]/', '$1', $texto);

        // Substitui \, por espaço
        $texto = str_replace('\,', ' ', $texto);

        // Destaca valores com MT
        $texto = preg_replace('/([0-9]{1,3}(?:\.[0-9]{3})*,[0-9]{2})\s?MT/', '<strong>$1 MT</strong>', $texto);

        // Substitui quebras de linha por <br>
        return nl2br(trim($texto));
    }

    public function historico()
{
    $usuarioId = session('usuario_id');

    if (!$usuarioId) {
        return response()->json(['error' => 'Usuário não autenticado'], 401);
    }

    $mensagens = Conversation::where('usuario_id', $usuarioId)
        ->orderBy('id', 'asc') // <-- aqui você troca por id se não usar created_at
        ->get();

    return response()->json($mensagens);
}



}
