<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        $user_message = $request->message;
        $usuario_id = session('usuario_id');




        // Chamar a API DeepSeek
        $response = $this->deepSeekService->chat($request->message);
        $aiResponse = $response['choices'][0]['message']['content'];


        // Salvar no banco de dados

        $conversation = new Conversation();
        $conversation->usuario_id = $usuario_id;
        $conversation->user_message = $user_message;
        $conversation->ai_response= $aiResponse;
        $conversation->save();



        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Operação realizada com sucesso'
            ],
            'response' => $aiResponse
        ]);


    }

    public function getHistory()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($conversations);

    }

    public function upload(Request $request, DeepSeekService $deepSeekService)
{
    $request->validate([
        'pdf' => 'required|mimes:pdf|max:10240', // 10MB
    ]);

    try {
        // Salva o PDF temporariamente
        $filePath = $request->file('pdf')->store('temp_pdfs');

        // Envia para a API da DeepSeek
        $apiResponse = $deepSeekService->sendPdfToApi(storage_path('app/' . $filePath));

        // Processa a resposta (ex.: extrai texto, metadados)
        $extractedText = $apiResponse['text'] ?? 'Texto não extraído.';
        $metadata = $apiResponse['metadata'] ?? [];

        // Salva no banco de dados
        $document = new Document();
        $document->user_id = auth()->id();
        $document->title = $request->file('pdf')->getClientOriginalName();
        $document->text_content = $extractedText;
        $document->file_path = $filePath; // Opcional: armazena o caminho do arquivo
        $document->save();

        // Limpa o arquivo temporário
        Storage::delete($filePath);

        return response()->json([
            'success' => true,
            'document_id' => $document->id,
            'extracted_data' => $extractedText
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Erro ao processar PDF: ' . $e->getMessage()
        ], 500);
    }

}
}
