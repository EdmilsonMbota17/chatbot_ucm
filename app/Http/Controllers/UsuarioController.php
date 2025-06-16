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




}

