<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Subempresa;
use Illuminate\Http\Request;

class SubempresaController extends Controller
{
    public function index()
    {
        return Subempresa::with('empresa')->get();
    }

    public function store(Request $req, Empresa $empresa)
    {
        $data = $req->validate([
            'nombre'      => 'required|string|max:255',
            'slug'        => 'required|string|unique:subempresas,slug',
            'descripcion' => 'nullable|string',
        ]);

        $sub = $empresa->subempresas()->create($data);
        return response()->json($sub, 201);
    }

    public function show(Subempresa $subempresa)
    {
        return $subempresa->load('sucursales');
    }

    public function update(Request $req, Subempresa $subempresa)
    {
        $data = $req->validate([
            'nombre'      => 'sometimes|required|string|max:255',
            'slug'        => "sometimes|required|string|unique:subempresas,slug,{$subempresa->id}",
            'descripcion' => 'nullable|string',
        ]);

        $subempresa->update($data);
        return response()->json($subempresa);
    }

    public function destroy(Subempresa $subempresa)
    {
        $subempresa->delete();
        return response()->json(['mensaje' => 'Subempresa eliminada.']);
    }

    public function sucursales(Subempresa $subempresa)
    {
        return response()->json($subempresa->load('sucursales'));
    }
}
