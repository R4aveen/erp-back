<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubempresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:subempresas,slug',
            'descripcion' => 'nullable|string',
        ];
    }

}
