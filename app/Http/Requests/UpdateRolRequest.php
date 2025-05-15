<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rolId = $this->route('rol')->id;

        return [
            'nombre' => "required|string|max:100|unique:roles,nombre,{$rolId}",
            'slug'   => "required|string|max:100|alpha_dash|unique:roles,slug,{$rolId}",
        ];
    }
}
