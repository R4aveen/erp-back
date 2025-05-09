<?php

namespace App\Http\Controllers;

use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Lista todas las empresas (solo para admins o gerentes globales)
    public function index()
    {
        return Empresa::all();
    }

    // Devuelve una empresa con sus subempresas y sucursales (GET /empresas/{id}/subempresas)
    public function subempresas($id)
    {
        $empresa = Empresa::with('subempresas.sucursales')->findOrFail($id);
        return response()->json($empresa);
    }

    // Devuelve una empresa con todas sus subempresas, sucursales y usuarios (GET /empresa/{id}/usuarios)
    public function listarUsuarios($id)
    {
        $empresa = Empresa::with('subempresas.sucursales.usuarios')->findOrFail($id);
        return response()->json($empresa);
    }
}
