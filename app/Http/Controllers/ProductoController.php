<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Crea un nuevo producto asociado a la empresa.
     */
    public function store(Request $request, Empresa $empresa)
    {
        // Validación de los datos de entrada
        $data = $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            // añade aquí otros campos que tengas en tu tabla productos
        ]);

        // Crea el producto (suponiendo que Empresa tiene hasMany Productos)
        $producto = $empresa->productos()->create($data);

        return response()->json([
            'mensaje'  => 'Producto creado correctamente',
            'producto' => $producto,
        ], 201);
    }

    /**
     * Actualiza un producto existente de la empresa.
     */
    public function update(Request $request, Empresa $empresa, Producto $producto)
    {
        // Opcional: verificar que $producto pertenezca a $empresa
        if ($producto->empresa_id !== $empresa->id) {
            return response()->json(['error' => 'Este producto no pertenece a la empresa'], 403);
        }

        $data = $request->validate([
            'nombre'        => 'sometimes|required|string|max:255',
            'descripcion'   => 'sometimes|nullable|string',
            'precio'        => 'sometimes|required|numeric|min:0',
            'stock'         => 'sometimes|required|integer|min:0',
            // otros campos...
        ]);

        $producto->update($data);

        return response()->json([
            'mensaje'  => 'Producto actualizado correctamente',
            'producto' => $producto,
        ]);
    }

    /**
     * Elimina un producto de la empresa.
     */
    public function destroy(Empresa $empresa, Producto $producto)
    {
        // Verificar pertenencia
        if ($producto->empresa_id !== $empresa->id) {
            return response()->json(['error' => 'Este producto no pertenece a la empresa'], 403);
        }

        $producto->delete();

        return response()->json([
            'mensaje' => 'Producto eliminado correctamente',
        ]);
    }
}
