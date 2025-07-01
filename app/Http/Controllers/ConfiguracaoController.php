<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class ConfiguracaoController extends Controller
{


       // Página inicial do perfil
       public function index()
       {
           $usuario = Usuario::find(session('usuario_id'));
           return view('configuracao.perfil');
       }

       // Alterar senha
       public function atualizarSenha(Request $request, $id)
       {

           $request->validate([
               'senha_atual' => 'required',
               'nova_senha' => 'required|min:6|confirmed',
           ]);

           $usuario = Usuario::findOrFail($id);

           if ($usuario->senha !== $request->senha_atual) {
               return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
           }

           $usuario->senha = $request->nova_senha;
           $usuario->save();

           return redirect()->url('/perfil')->with('success', 'Senha alterada com sucesso.');
       }


    // Alterar foto de perfil
    public function alterarFotoPerfil(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Máximo de 2MB
        ]);

        $usuario = Usuario::find(session('usuario_id'));

        if ($request->hasFile('foto')) {
            // Remover a foto antiga, se existir
            if ($usuario->foto_perfil) {
                Storage::delete('public/' . $usuario->foto_perfil);
            }

            // Salvar a nova foto
            $imagemPath = $request->file('foto')->store('fotos-perfil', 'public');
            $usuario->update(['foto_perfil' => $imagemPath]);
        }

        return redirect()->route('perfil')->with('success', 'Foto de perfil atualizada com sucesso!');
    }

    // Alterar tema
    public function alterarTema(Request $request)
    {
        $request->validate([
            'modo' => 'required',
        ]);

        $usuario = Usuario::find(session('usuario_id'));
        $usuario->update(['modo_noturno' => $request->modo]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tema atualizado!',

        ]);
    }



}

