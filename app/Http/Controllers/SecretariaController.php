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

            // Salva dados da secretaria na sessão
            session([
                'secretaria_id' =>  true, // valor simbólico, já que não está vindo do banco
                'secretaria_nome' => 'Secretaria Geral'
            ]);

            return redirect('/documentos')->with('success', 'Login realizado com sucesso!');
        }

        return back()->with('error', 'Credenciais inválidas.');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/')->with('success', 'Sessão encerrada com sucesso.');
    }

    public function documentos()
    {
        if (!session()->has('secretaria_id')) {
            return redirect('/')->with('error', 'Você precisa estar logado como usuário.');
        }

        return view('secretaria.index');
    }


}
