<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSucursalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'         => 'sometimes|required|string|max:255',
            'subempresa_id'  => 'sometimes|required|exists:subempresas,id',
            'direccion'      => 'nullable|string',
            'telefono'       => 'nullable|string|max:20',
        ];
    }
}
