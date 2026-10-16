<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome_cliente',
        'telefone_cliente',
        'email_cliente',
        'senha_cliente',
    ];

    
}
