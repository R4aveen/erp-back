<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtiene el ID de la empresa del route-model binding
        $empresaId = $this->route('empresa')->id;

        return [
            'nombre'      => 'sometimes|required|string|max:255',
            'rut'         => ['sometimes', 'required', 'string', Rule::unique('empresas', 'rut')->ignore($empresaId)],
            'descripcion' => 'nullable|string',
        ];
    }
}