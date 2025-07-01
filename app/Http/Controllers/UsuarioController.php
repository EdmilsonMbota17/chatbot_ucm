<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

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

    public function registrar(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:usuario',
            'senha' => 'required|min:6',
        ]);

        Usuario::create([
            'email' => $request->email,
            'senha' => ($request->senha),
            'nome' => $request->nome,
            'pergunta_recuperacao' => ($request->pergunta_recuperacao),
            'resposta_recuperacao' => ($request->resposta_recuperacao),
        ]);


        return redirect('/')->with('success', 'Conta criada com sucesso! Faça login.');
    }

    public function verificarRecuperacao(Request $request)
    {
        // Debug inicial
         // Verifique se os dados estão chegando corretamente

        $usuario = Usuario::where('email', trim($request->email))->first();

        if ($usuario->pergunta_recuperacao !== $request->pergunta_recuperacao) {
            return back()->with('error', 'Pergunta de segurança incorreta.');
        }

        if (!Hash::check($request->resposta_recuperacao, $usuario->resposta_recuperacao)) {
            return back()->with('error', 'Resposta incorreta.');
        }


        if (!Hash::check($request->resposta_recuperacao, $usuario->resposta_recuperacao)) {
            return back()->with('error', 'Resposta incorreta.');
        }

        return redirect()->route('alterarSenha', ['email' => $usuario->email]);
    }

    public function alterarSenha(Request $request)
{
    $usuarioId = session('usuario_id');
    $usuario = Usuario::find($usuarioId);

    if (!$usuario) {
        return back()->withErrors(['Usuário não encontrado.']);
    }

    $senhaAtual = $request->input('senha_atual');
    $novaSenha = $request->input('nova_senha');
    $confirmarSenha = $request->input('nova_senha_confirmation');

    // Verifica se a senha atual é igual à que está no banco (texto puro)
    if ($senhaAtual !== $usuario->senha) {
        return back()->withErrors(['A senha atual está incorreta.']);
    }

    if ($novaSenha !== $confirmarSenha) {
        return back()->withErrors(['A nova senha e a confirmação não coincidem.']);
    }

    // Atualiza a senha no banco (sem hash)
    $usuario->senha = $novaSenha;
    $usuario->save();

    return back()->with('success', 'Senha alterada com sucesso!');
}
public function logout()
{
    session()->flush(); // Remove todos os dados da sessão
    return redirect('/')->with('success', 'Sessão encerrada com sucesso.');
}




}

