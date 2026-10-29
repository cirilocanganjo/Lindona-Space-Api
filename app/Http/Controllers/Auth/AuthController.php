<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ClientCreateAccountRequest;
use App\Http\Requests\UpdateAuthenticatedUserRequest;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Str;

class AuthController extends Controller
{
  public function login(AuthRequest $request): JsonResponse
{
    $login = $request->input('email');
    $password = $request->input('password');

    $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

    $employeeField = $isEmail ? 'email_funcionario' : 'telefone_funcionario';
    $clientField = $isEmail ? 'email_cliente' : 'telefone_cliente';

    $funcionario = Funcionario::query()
        ->where($employeeField, $login)
        ->first();

    $cliente = Cliente::query()
        ->where($clientField, $login)
        ->first();

    $user = null;

    if ($funcionario && Hash::check($password, $funcionario->senha_funcionario)) {
        $user = User::query()
            ->where('funcionario_id', $funcionario->id)
            ->first();
    } elseif ($cliente && Hash::check($password, $cliente->senha_cliente)) {
        $user = User::query()
            ->where('cliente_id', $cliente->id)
            ->first();
    
    } else {
        return response()->json([
            'message' => 'Credenciais inválidas, tente novamente.'
        ], 401);
    }

    if (!$user) {
        return response()->json([
            'message' => 'Utilizador não encontrado.'
        ], 404);
    }

    $token = auth('api')->login($user);

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',        
        'user' => [
            'isClient' => $user->cliente_id ? true : false,
            'isEmployee' => $user->funcionario_id ? true : false,
            'authenticatedUser' => $user->cliente ? $user->cliente : $user->funcionario,
        ]
    ]);
}


public function register(ClientCreateAccountRequest $request): JsonResponse
{
    $photo_name = null;

    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $photo = $request->file('photo');
        $photo_name = Str::uuid() . '.' . $photo->extension();
        $photo->storeAs('img', $photo_name, 'public');
    }

    DB::transaction(function () use ($request, $photo_name) {
        $cliente = Cliente::create([
            'nome_cliente' => $request->input('name'),
            'email_cliente' => $request->input('email'),
            'telefone_cliente' => $request->input('phone'),
            'senha_cliente' => Hash::make($request->input('password')),
        ]);

        User::create([
            'cliente_id' => $cliente->id,
            'profile_photo' => $photo_name,
        ]);
    });

    return response()->json([
        'message' => 'A sua conta foi criada com sucesso, faça login para continuar.',
    ], 201);
}

public function logout(Request $request): JsonResponse
{
    auth('api')->logout();

    return response()->json([
        'message' => 'Logout realizado com sucesso.'
    ]);

}

public function getAuthenticatedUser (): JsonResponse
{
    $user = auth()->user()->load('cliente');

    return response()->json([
        'data' => $user
    ]);
}




public function updateAuthenticatedUser(UpdateAuthenticatedUserRequest $request): JsonResponse
{
    $user = auth('api')->user()->load('cliente');
    $cliente = $user->cliente;
    $validated = $request->validated();

    $oldPhoto = $user->profile_photo;
    $newPhoto = null;

    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $photo = $request->file('photo');
        $newPhoto = Str::uuid() . '.' . $photo->extension();
        $photo->storeAs('img', $newPhoto, 'public');
    }

    try {
        DB::transaction(function () use ($cliente, $user, $validated, $newPhoto) {
            $cliente->update([
                'nome_cliente' => $validated['name'],
                'email_cliente' => $validated['email'],
                'telefone_cliente' => $validated['phone'],
                ...(!empty($validated['password']) ? [
                    'senha_cliente' => Hash::make($validated['password']),
                ] : []),
            ]);

            if ($newPhoto !== null) {
                $user->update([
                    'profile_photo' => $newPhoto,
                ]);
            }
        });
    } catch (\Throwable $exception) {
        if ($newPhoto !== null) {
            Storage::disk('public')->delete('img/' . $newPhoto);
        }

        throw $exception;
    }

    if ($newPhoto !== null && $oldPhoto) {
        Storage::disk('public')->delete('img/' . $oldPhoto);
    }

    $cliente->refresh();

    return response()->json([
        'message' => 'Os seus dados foram atualizados com sucesso.',
        'data' => [
            'name' => $cliente->nome_cliente,
            'email' => $cliente->email_cliente,
            'phone' => $cliente->telefone_cliente,
            'profile_photo' => $user->fresh()->profile_photo,
        ],
    ]);
}





}


