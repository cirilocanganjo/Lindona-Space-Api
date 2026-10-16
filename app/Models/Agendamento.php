<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    
  protected $fillable = [
        'data',
        'hora',
        'status',
        'funcionario_id',
        'cliente_id',
        'servico_id',
    ];
}
