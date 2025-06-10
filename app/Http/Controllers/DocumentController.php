<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'document' => 'required|mimes:pdf|max:10240', // 10MB
            ]);

            $pdf = $request->file('document');
            $fileHash = md5_file($pdf->path());

            // Verifica se documento já existe
            if ($existingDoc = Document::where('file_hash', $fileHash)->first()) {
                return response()->json([
                    'message' => 'Documento já existe',
                    'document' => $existingDoc
                ], 409);
            }

            // Extrai texto do PDF
            $parser = new Parser();
            $pdfText = $parser->parseFile($pdf->path())->getText();

            // Salva documento
            $document = Document::create([
                'title' => $request->input('title', $pdf->getClientOriginalName()),
                'original_name' => $pdf->getClientOriginalName(),
                'file_path' => $pdf->store('documents'),
                'file_hash' => $fileHash,
                'file_size' => $pdf->getSize(),
                'text_content' => $pdfText,
            ]);

            return response()->json([
                'message' => 'Documento enviado com sucesso',
                'document' => $document
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno ao enviar documento',
                'detalhes' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Document $document)
    {
        return response()->json([
            'document' => [
                'id' => $document->id,
                'title' => $document->title,
                'content' => $document->text_content,
            ]
        ]);
    }

    public function query(Document $document, Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.deepseek.key'),
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Você é um assistente de análise de documentos. Baseie sua resposta EXCLUSIVAMENTE no seguinte conteúdo:\n\n" .
                                Str::limit($document->text_content, 30000)
                ],
                [
                    'role' => 'user',
                    'content' => $request->question
                ]
            ],
            'temperature' => 0.3,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Falha na consulta à API',
                'details' => $response->json()
            ], 502);
        }

        return $response->json();
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $documents = Document::where(function ($query) use ($keyword) {
            $query->where('text_content', 'LIKE', "%{$keyword}%")
                  ->orWhere('title', 'LIKE', "%{$keyword}%");
        })->get();

        return response()->json($documents);
    }

    public function destroy(Document $document)
    {
        Storage::delete($document->file_path);
        $document->delete();

        return response()->json([
            'message' => 'Documento removido com sucesso'
        ]);
    }
}

