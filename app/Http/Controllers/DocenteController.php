<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Document;
use App\Models\Conversation;
use App\Services\DeepSeekService;

class DocenteController extends Controller
{
    protected $deepSeekService;

    // Injetando o DeepSeekService no construtor
    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    // Listar todos os docentes
    public function index()
    {
        $docentes = Docente::all();
        return view('docente.index', compact('docentes'));
    }

    // Mostrar formulário para criar docente
    public function create()
    {
        return view('docente.create');
    }

    // Salvar novo docente
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:docentes,email',
            'senha' => 'required|min:6',
            'nome' => 'required|string|max:255',
        ]);

        Docente::create([
            'email' => $request->email,
            'nome' => $request->nome,
            'senha' => bcrypt($request->senha),
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente cadastrado com sucesso!');
    }

    // Função para responder perguntas via IA para o docente
    public function pergunta(Request $request)
{
    try {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user_message = $request->message;
        $docente_id = session('docente_id'); // Ajuste conforme sua sessão

        $documentos = Document::all();
        $contexto = $documentos->pluck('text_content')->implode("\n\n");

        $prompt = "Com base nesse conteúdo: \n$contexto\n\nPergunta: $user_message";

        $response = $this->deepSeekService->chat($prompt);

        $aiResponse = $response['choices'][0]['message']['content'] ?? 'Sem resposta';

        Conversation::create([
            'docente_id' => $docente_id,
            'user_message' => $user_message,
            'ai_response' => $aiResponse,
        ]);

        return response()->json([
            'status' => 'success',
            'response' => $aiResponse,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
}
}
