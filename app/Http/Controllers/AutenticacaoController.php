<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Docente;

class AutenticacaoController extends Controller
{
    // Página de login para usuários comuns
    public function index()
    {
        return view('autenticacao.login');
    }

    // Autenticação para usuários comuns
    public function autenticar(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        if ($request->senha !== $usuario->senha) {
            return back()->withErrors(['senha' => 'Senha incorreta.']);
        }

        session([
            'nome' => $usuario->nome,
            'usuario_id' => $usuario->id,
        ]);

        return redirect()->to('/chat')->with('success', 'Login realizado com sucesso!');
    }

    // Página de login para docentes
    public function indexDocente()
    {
        return view('autenticacaodocente.login');
    }

    // Autenticação para docentes
    public function autenticardocente(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $docente = Docente::where('email', $request->email)->first();

        if (!$docente) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        if ($request->senha !== $docente->senha) {
            return back()->withErrors(['senha' => 'Senha incorreta.']);
        }

        session(['nome' => $docente->nome]);
        session(['docente_id' => $docente->id]);

        return redirect()->to('/chatdocente')->with('success', 'Login realizado com sucesso!');
    }

    // Página de login para secretaria
public function indexSecretaria()
{
    return view('autenticasecretaria.login');
}

// Autenticação para secretaria
public function autenticarSecretaria(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'senha' => 'required',
    ]);

    $secretaria = Secretaria::where('email', $request->email)->first();

    if (!$secretaria) {
        return back()->withErrors(['email' => 'Usuário não encontrado.']);
    }

    if ($request->senha !== $secretaria->senha) {
        return back()->withErrors(['senha' => 'Senha incorreta.']);
    }

    // Somente aqui é seguro salvar na sessão
    session([
        'secretaria_id' => $secretaria->id,
        'secretaria_nome' => $secretaria->nome ?? 'Secretaria'
    ]);

    return redirect()->to('/documentos')->with('success', 'Login realizado com sucesso!');
}
}

