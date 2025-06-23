<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    // Exibe a página de login da secretaria
    public function indexSecretaria()
    {
        return view('autenticasecretaria.login');
    }

    // Faz o login da secretaria
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        // Aqui você pode autenticar com base nos dados do banco
        // Exemplo básico de verificação
        if ($request->email === 'secretaria@ucm.ac.mz' && $request->senha === '123456') {
            session(['secretaria_logada' => true]);
            return redirect('/documentos')->with('success', 'Login realizado com sucesso!');
        }

        return back()->with('error', 'Credenciais inválidas.');
    }
}
