<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'endereco';

    protected $primaryKey = 'id_endereco';

    protected $fillable = [
        'cep_endereco',
        'complemento_endereco',
        'cidade_endereco',
        'logradouro_endereco',
        'estado_endereco',
        'bairro_endereco',
        'numero_endereco'
    ];

    public function instituicoes()
    {
        return $this->hasMany(Instituicao::class, 'id_endereco');
    }

    public function voluntarios()
    {
        return $this->hasMany(Voluntario::class, 'id_endereco');
    }
}
