<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateAuthenticatedUserRequest extends FormRequest
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
         $user = auth()->user()->load('cliente');
        
          return [
            'name' => 'required|string|max:255',
            'email' => [
                "required",
                "email",
                Rule::unique('clientes', 'email_cliente')->ignore($user->cliente->id),                
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clientes', 'telefone_cliente')->ignore($user->cliente->id),
            ],
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'nullable|required_with:password|same:password',
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
            
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.string' => 'A senha deve ser um texto válido.',
            'password_confirmation.required_with' => 'Confirme a nova senha.',
            'password_confirmation.same' => 'A confirmação da senha não corresponde.',
            
            'photo.image' => 'O arquivo enviado deve ser uma imagem.',
            'photo.mimes' => 'A imagem deve estar no formato jpeg, png, jpg ou gif.',
            'photo.max' => 'A imagem não pode ter mais de 5MB.',
        ];
    }

    /*


    [
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            
        ],
        'phone' => ['required', 'string', 'max:30'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'password' => ['nullable', 'string', 'min:8'],
    ]
    
    
    
    
    */
}
