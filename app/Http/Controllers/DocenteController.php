<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente; // Certifique-se de ter criado esse model

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::all();
        return view('docente.index', compact('docente'));
    }

    public function create()
    {
        return view('docente.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:docente',
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

