<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $fillable = [
        'nome_funcionario',
        'telefone_funcionario',
        'email_funcionario',
        'senha_funcionario',
    ];
}
