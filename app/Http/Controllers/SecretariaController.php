<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretariaController extends Controller
{

    public function indexSecretaria()
    {
        return view('autenticasecretaria.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        if ($request->email === 'secretaria@ucm.ac.mz' && $request->senha === '123456') {
            session(['secretaria_logada' => true]);
            return redirect('/documentos')->with('success', 'Login realizado com sucesso!');
        }

        return back()->with('error', 'Credenciais inválidas.');
    }
    public function logout()
{
    session()->flush();
    return redirect('/')->with('success', 'Sessão encerrada com sucesso.');
}

}
