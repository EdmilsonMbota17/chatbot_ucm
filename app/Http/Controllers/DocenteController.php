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
public function logout()
{
    session()->flush();
    return redirect('/')->with('success', 'Sessão encerrada com sucesso.');
}

public function chatdocente()
{
    if (!session()->has('docente_id')) {
        return redirect('/docentelogin')->with('error', 'Você precisa estar logado como usuário.');
      }
    else{
        return view('conversadocente.index');

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

}
