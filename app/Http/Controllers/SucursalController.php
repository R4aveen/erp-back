<?php

namespace App\Http\Controllers;

use App\Models\Subempresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function usuarios($id)
    {
        $sucursal = Sucursal::with('usuarios')->findOrFail($id);
        return response()->json($sucursal);
    }
    public function store(Request $req, Subempresa $subempresa)
    {
        $validated = $req->validate([
            'nombre' => 'required|string|max:255',
            // …
        ]);

        $suc = $subempresa->sucursales()->create($validated);

        return response()->json($suc, 201);
    }

}
