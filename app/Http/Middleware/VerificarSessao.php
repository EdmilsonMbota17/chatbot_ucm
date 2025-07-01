<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSessao
{
    public function handle(Request $request, Closure $next, $tipo)
    {
        switch ($tipo) {
            case 'usuario':
                if (!session()->has('usuario_id')) {
                    return redirect('/')->with('error', 'Você precisa estar logado como usuário.');
                }
                break;

            case 'docente':
                if (!session()->has('docente_id')) {
                    return redirect('/docentelogin')->with('error', 'Você precisa estar logado como docente.');
                }
                break;

            case 'secretaria':
                if (!session()->has('secretaria_id')) {
                    return redirect('/login-secretaria')->with('error', 'Você precisa estar logado como secretaria.');
                }
                break;
        }

        return $next($request);
    }
}


