<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function subempresas($id)
    {
        $empresa = Empresa::with('subempresas.sucursales')->findOrFail($id);
        return response()->json($empresa);
    }

    public function listarUsuarios($id)
    {
        $empresa = Empresa::with('subempresas.sucursales.usuarios')->findOrFail($id);
        return response()->json($empresa);
    }
}
