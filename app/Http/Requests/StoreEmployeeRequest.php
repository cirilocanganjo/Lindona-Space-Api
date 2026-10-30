<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email_funcionario',
            'phone' => 'required|string|max:20|unique:funcionarios,telefone_funcionario',           
            'salary' => 'required|integer',
            'role' => 'required|string',            
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo de nome é obrigatório.',

            'email.required' => 'O campo de email é obrigatório.',
            'email.email' => 'O campo de email deve ser um endereço de email válido.',
            'email.unique' => 'Este email já está em uso.',

            'phone.required' => 'O campo de telefone é obrigatório.',
            'phone.unique' => 'Este telefone já está em uso.',

            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',

            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'password_confirmation.required' => 'O campo de confirmação de senha é obrigatório.',
            'password_confirmation.same' => 'A confirmação da senha não corresponde.',

            'salary.required' => 'O campo salário é obrigatório',
            'salary.integer' => 'O campo salário deve ser um número',

            'role.required' => 'O campo cargo é obrigatório',
            'role.string' => 'O campo cargo deve ser um texto válido',          
                 
            'photo.image' => 'O arquivo enviado deve ser uma imagem.',
            'photo.mimes' => 'A imagem deve estar no formato jpeg, png, jpg ou gif.',
            'photo.max' => 'A imagem não pode ter mais de 5MB.',
        ];
    }
}
