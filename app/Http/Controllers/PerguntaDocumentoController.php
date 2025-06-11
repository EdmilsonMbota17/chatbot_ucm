<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class PerguntaDocumentoController extends Controller
{
    public function perguntar(Request $request)
    {
        $pergunta = $request->input('pergunta');

        if (!$pergunta || trim($pergunta) === '') {
            return response()->json(['error' => 'Por favor, digite uma pergunta.'], 400);
        }

        $documentos = Document::all();

        if ($documentos->isEmpty()) {
            return response()->json(['error' => 'Nenhum documento encontrado no banco de dados.'], 404);
        }

        $contexto = $documentos->pluck('text_content')->implode("\n\n");

        if (empty($contexto)) {
            return response()->json(['error' => 'O conteúdo dos documentos está vazio.'], 422);
        }

        $resposta = app(DeepseekController::class)->askDeepSeek("Com base nesse conteúdo: \n$contexto\n\nPergunta: $pergunta");

        return response()->json([
            'answer' => $resposta['answer'] ?? 'Não foi possível obter uma resposta da IA.',
        ]);
    }
}




