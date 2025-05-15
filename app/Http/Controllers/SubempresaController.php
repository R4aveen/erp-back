<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubempresaRequest;
use App\Http\Requests\UpdateSubempresaRequest;
use App\Models\Empresa;
use App\Models\Subempresa;
use Illuminate\Http\Request;

class SubempresaController extends Controller
{
    public function index()
    {
        return Subempresa::with('empresa')->get();
    }

    public function store(StoreSubempresaRequest $request, Empresa $empresa)
    {
        $data = $request->validated();
        $sub = $empresa->subempresas()->create($data);
        return response()->json($sub, 201);
    }

    public function show(Subempresa $subempresa)
    {
        return $subempresa->load('sucursales');
    }

    public function update(UpdateSubempresaRequest $request, Subempresa $subempresa)
    {
        $data = $request->validated();
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
