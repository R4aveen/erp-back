<?php

namespace App\Http\Controllers;

use App\Models\Subempresa;
use Illuminate\Http\Request;

class SubempresaController extends Controller
{
    public function sucursales($id)
    {
        $subempresa = Subempresa::with('sucursales')->findOrFail($id);
        return response()->json($subempresa);
    }
}
