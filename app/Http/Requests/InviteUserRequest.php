<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:255',
            'email'       => 'required|email|unique:usuarios,email',
            'rol_id'      => 'required|exists:roles,id',
            'sucursal_id' => 'nullable|exists:sucursales,id',
        ];
    }
}
