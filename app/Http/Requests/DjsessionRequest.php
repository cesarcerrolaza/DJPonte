<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DjsessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'active'      => 'boolean',
            'venue'       => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'city'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_time'  => 'nullable|date',
            'end_time'    => 'nullable|date|after:start_time',
            'song_request_timeout' => 'nullable|integer|min:0|max:7200',
        ];
        
        // Para la regla 'code', necesitamos verificar si estamos en una actualización
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Si estamos actualizando, ignoramos el registro actual en la validación unique
            $djsessionId = $this->route('djsession')->id;
            $rules['code'] = "nullable|string|max:255|unique:djsessions,code,{$djsessionId}";
        } else {
            // Si estamos creando, la regla original
            $rules['code'] = 'nullable|string|max:255|unique:djsessions,code';
        }
        
        return $rules;
    }
}
