<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSucursalRequest;
use App\Http\Requests\UpdateSucursalRequest;
use App\Models\Subempresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        return Sucursal::with('subempresa')->get();
    }

    public function store(StoreSucursalRequest $req, Subempresa $subempresa)
    {
        $data = $req->validate();
        $suc = $subempresa->sucursales()->create($data);
        return response()->json($suc, 201);
    }

    public function show(Sucursal $sucursal)
    {
        return $sucursal->load('usuarios');
    }

    public function update(UpdateSucursalRequest $req, Sucursal $sucursal)
    {
        $data = $req->validate();
        $sucursal->update($data);
        return response()->json($sucursal);
    }

    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();
        return response()->json(['mensaje' => 'Sucursal eliminada.']);
    }

    public function usuarios(Sucursal $sucursal)
    {
        return response()->json($sucursal->load('usuarios'));
    }
}
