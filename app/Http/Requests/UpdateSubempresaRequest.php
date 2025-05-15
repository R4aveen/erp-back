<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubempresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'sometimes|required|string|max:255',
            'empresa_id'  => 'sometimes|required|exists:empresas,id',
            'descripcion' => 'nullable|string',
        ];
    }
}