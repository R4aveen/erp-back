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
    public function store(Request $request, Subempresa $subempresa)
        {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'direccion' => 'nullable|string|max:255',
            ]);

            $sucursal = $subempresa->sucursales()->create($validated);

            return response()->json($sucursal, 201);
        }

}
