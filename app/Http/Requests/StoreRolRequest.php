<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // el middleware checkPermiso ya controla el acceso
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100|unique:roles,nombre',
            'slug'   => 'required|string|max:100|alpha_dash|unique:roles,slug',
        ];
    }
}
