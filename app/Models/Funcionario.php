<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;


#[Hidden(['senha_funcionario'])]
class Funcionario extends Model
{
    protected $fillable = [
        'nome_funcionario',
        'telefone_funcionario',
        'email_funcionario',
        'senha_funcionario',
    ];
}
