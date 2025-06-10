<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes'; // Nome da tabela no banco de dados
    public $timestamps = false;    // Define se usa created_at e updated_at

    protected $fillable = [
        'email',
        'nome',
        'senha',
    ];

    // Criptografa a senha automaticamente ao definir
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = bcrypt($value);
    }
}
