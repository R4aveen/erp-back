<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Subempresa;
use Illuminate\Http\Request;

class SubempresaController extends Controller
{
    protected $fillable = ['nombre', 'slug', 'descripcion', 'empresa_id'];
    
    public function sucursales($id)
    {
        $subempresa = Subempresa::with('sucursales')->findOrFail($id);
        return response()->json($subempresa);
    }
    public function store(Request $req, Empresa $empresa)
    {
        $validated = $req->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subempresas,slug',
            'descripcion' => 'nullable|string',
        ]);

        $sub = $empresa->subempresas()->create($validated);

        return response()->json($sub, 201);
    }


}
