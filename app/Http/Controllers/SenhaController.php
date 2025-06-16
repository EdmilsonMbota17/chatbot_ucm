<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class SenhaController extends Controller
{
    // Exibe o formulário para o usuário digitar o e-mail
    public function formEmail()
    {
        return view('recuperarsenha.email');
    }

    // Processa o e-mail enviado e redireciona para a pergunta de recuperação
    public function verificarEmail(Request $request)
    {
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->with('erro', 'E-mail não encontrado.');
        }

        return redirect()->route('senha.pergunta', ['id' => $usuario->id]);
    }

    // Exibe o formulário com a pergunta de recuperação
    public function formPergunta($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('recuperarsenha.responder_pergunta', compact('usuario'));
    }

    // Verifica a resposta da pergunta de recuperação
    public function verificarResposta(Request $request)
    {
        $usuario = Usuario::find($request->usuario_id);

        if (!$usuario || $request->resposta !== $usuario->resposta_recuperacao) {

            return back()->with('erro', 'Resposta incorreta.');
        }

        return redirect()->route('senha.redefinir', ['id' => $usuario->id]);
    }

    // Exibe o formulário de redefinição de senha
    public function mostrarFormularioRedefinicao($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('recuperarsenha.redefinir', compact('usuario'));
    }

    // Atualiza a senha no banco de dados
    public function atualizarSenha(Request $request, $id)
    {
        // Validação
        $request->validate([
            'nova_senha' => 'required|string|min:6|same:confirmar_senha',
        ]);

        // Encontrar o usuário
        $usuario = Usuario::findOrFail($id);

        // Atualizar a senha
        $usuario->senha = Hash::make($request->nova_senha);
        $usuario->save();

        // Redirecionar com mensagem
        return redirect('/')->with('success', 'Senha recuperada com sucesso! Faça login.');

    }

}
