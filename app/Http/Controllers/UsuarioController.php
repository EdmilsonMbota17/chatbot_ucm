<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Importação correta do Model

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuario.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuario',
            'senha' => 'required|min:6',
        ]);
        dd($request->all());

        Usuario::create([
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'trocar_perfil' => 1,
            'trocar_senha' => 1,
            'modo_noturno' => 'diurno',
            'nome' => $request->nome,
            'pergunta_recuperacao' => $request->pergunta_recuperacao,
            'resposta_recuperacao' => bcrypt($request->resposta_recuperacao),
        ]);


        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function verificarRecuperacao(Request $request)
{
    dd($request->all());
    $usuario = Usuario::where('email', trim($request->email))->first();

    if (!$usuario) {
        return back()->with('error', 'Usuário não encontrado.');
    }

    // Verifica se a pergunta bate com a cadastrada
    if ($usuario->pergunta_recuperacao !== $request->pergunta) {
        return back()->with('error', 'Pergunta de segurança incorreta.');
    }

    // Verifica se a resposta está correta
    if (!Hash::check($request->resposta, $usuario->resposta_recuperacao)) {
        return back()->with('error', 'Resposta incorreta.');
    }

    // Tudo certo: redireciona para página de redefinição de senha
    return redirect()->route('alterarSenha', ['email' => $usuario->email]);


}
}
