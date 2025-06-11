<?php

// =======================
// DocumentController.php
// =======================

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
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

            if ($existingDoc = Document::where('file_hash', $fileHash)->first()) {
                return response()->json([
                    'message' => 'Documento já existe',
                    'document' => $existingDoc
                ], 409);
            }

            $parser = new Parser();
            $pdfText = $parser->parseFile($pdf->path())->getText();

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

    public function destroy(Document $document)
    {
        Storage::delete($document->file_path);
        $document->delete();

        return response()->json([
            'message' => 'Documento removido com sucesso'
        ]);
    }
}

