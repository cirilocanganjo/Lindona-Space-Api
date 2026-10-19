<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Hidden(['senha_cliente'])]

class Cliente extends Model
{
    protected $fillable = [
        'nome_cliente',
        'telefone_cliente',
        'email_cliente',
        'senha_cliente',
    ];

    
}
