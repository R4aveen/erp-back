<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function usuarios($id)
    {
        $sucursal = Sucursal::with('usuarios')->findOrFail($id);
        return response()->json($sucursal);
    }
}
