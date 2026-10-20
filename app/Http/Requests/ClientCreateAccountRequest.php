<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClientCreateAccountRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_cliente' => 'required|string|max:255',
            'email_cliente' => 'required|email|unique:clientes,email_cliente',
            'telefone_cliente' => 'required|string|max:20|unique:clientes,telefone_cliente',
            'senha_cliente' => 'required|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'nome_cliente.required' => 'O campo de nome é obrigatório.',
            'email_cliente.required' => 'O campo de email é obrigatório.',
            'email_cliente.email' => 'O campo de email deve ser um endereço de email válido.',
            'email_cliente.unique' => 'Este email já está em uso.',
            'telefone_cliente.required' => 'O campo de telefone é obrigatório.',
            'telefone_cliente.unique' => 'Este telefone já está em uso.',
            'senha_cliente.required' => 'O campo de senha é obrigatório.',
            'senha_cliente.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'senha_cliente.confirmed' => 'A confirmação da senha não corresponde.',
            'photo.image' => 'O arquivo enviado deve ser uma imagem.',
            'photo.mimes' => 'A imagem deve estar no formato jpeg, png, jpg ou gif.',
            'photo.max' => 'A imagem não pode ter mais de 5MB.',
        ];
    }
}
