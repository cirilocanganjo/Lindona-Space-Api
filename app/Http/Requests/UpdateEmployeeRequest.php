<?php

namespace App\Http\Requests;

use \App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class UpdateEmployeeRequest extends FormRequest
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
    // id da rota = id da tabela users
    $userId = $this->route('id') 
           ?? $this->route('user') 
           ?? $this->route('funcionario');

    if (is_object($userId)) {
        $userId = $userId->id;
    }

    $user = User::query()->find($userId);  // Busca o funcionario_id ligado a esse user
    $funcionarioId = $user?->funcionario_id;

    return [
        'name' => 'required|string|max:255',        
        'email' => [
            "required",
            "email",
            Rule::unique('funcionarios', 'email_funcionario')->ignore($funcionarioId),
        ],        
        'phone' => [
            'required',
            'string',
            'max:20',
            Rule::unique('funcionarios', 'telefone_funcionario')->ignore($funcionarioId),
        ],
        'role' => 'required|string|max:255',
        'salary' => 'required|numeric|min:0|max:99999999.99',
        'status' => 'required|boolean',

        'password' => 'nullable|string|min:6',
        'password_confirmation' => 'nullable|required_with:password|same:password',

        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'O campo de nome é obrigatório.',
       
        'email.required' => 'O campo de email é obrigatório.',
        'email.email' => 'O campo de email deve ser um endereço válido.',
        'email.unique' => 'Este email já está em uso.',
        
        'phone.required' => 'O campo de telefone é obrigatório.',
        'phone.unique' => 'Este telefone já está em uso.',

        'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
        'password.string' => 'A senha deve ser um texto válido.',
        'password_confirmation.required_with' => 'Confirme a nova senha.',
        'password_confirmation.same' => 'A confirmação da senha não corresponde.',
       
        'photo.image' => 'O arquivo deve ser uma imagem.',
        'photo.mimes' => 'Formato inválido. Use jpg, png ou webp.',
        'photo.max' => 'A imagem não pode ter mais de 5MB.',
    ];
}




}
