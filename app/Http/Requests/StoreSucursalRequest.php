<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSucursalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'         => 'required|string|max:255',
            'subempresa_id'  => 'required|exists:subempresas,id',
            'direccion'      => 'nullable|string',
            'telefono'       => 'nullable|string|max:20',
        ];
    }
}
