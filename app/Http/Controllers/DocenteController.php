<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Document;
use Illuminate\Support\Facades\Http;

class DocenteController extends Controller
{
    // Listar todos os docentes
    public function index()
    {
        $docentes = Docente::all();
        return view('docente.index', compact('docentes'));
    }

    // Mostrar formulário para criar docente
    public function create()
    {
        return view('docente.create');
    }

    // Salvar novo docente
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:docentes,email',
            'senha' => 'required|min:6',
            'nome' => 'required|string|max:255',
        ]);

        Docente::create([
            'email' => $request->email,
            'nome' => $request->nome,
            'senha' => bcrypt($request->senha),
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente cadastrado com sucesso!');
    }




}





