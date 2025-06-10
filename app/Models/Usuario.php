<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'senha',
        'trocar_perfil',
        'trocar_senha',
        'modo_noturno',
        'foto_perfil',
        'nome',
        'pergunta_recuperacao',
        'resposta_recuperacao',
    ];


    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = bcrypt($value);
    }
}
