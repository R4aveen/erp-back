<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes afinar permisos aquí o usar middleware de autorización
        return true;
    }

    /**
     * Reglas de validación para crear una Empresa.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:255',
            'rut'         => 'required|string|unique:empresas,rut',
            'descripcion' => 'nullable|string',
        ];
    }
}