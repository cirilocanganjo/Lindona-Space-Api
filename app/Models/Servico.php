<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'nome_servico',
        'descricao_servico',
        'preco_servico',
        'categoria_id',
    ];
}
