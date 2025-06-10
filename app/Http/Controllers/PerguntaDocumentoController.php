<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class PerguntaDocumentoController extends Controller
{
    public function perguntar(Request $request, $documentId)
    {
        // Busca o documento no banco
        $document = Document::find($documentId);

        if (!$document) {
            return response()->json(['error' => 'Documento não encontrado.'], 404);
        }

        $pergunta = $request->input('pergunta');

        if (!$pergunta || trim($pergunta) === '') {
            return response()->json(['error' => 'Por favor, digite uma pergunta.'], 400);
        }

        $contexto = $document->text_content;

        if (empty($contexto)) {
            return response()->json(['error' => 'O conteúdo do documento está vazio.'], 422);
        }

        // Envia para o DeepseekController processar a pergunta
        $resposta = app(DeepseekController::class)->askDeepSeek("Com base nesse conteúdo: \n$contexto\n\nPergunta: $pergunta");

        return response()->json([
            'answer' => $resposta['answer'] ?? 'Não foi possível obter uma resposta da IA.',
        ]);
    }
}

