<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';

    protected $primaryKey = 'id_evento';

    protected $fillable = [
        'descricao_evento',
        'data_hora_evento',
        'id_instituicao',
        'id_endereco',
        'data_hora_limite_evento',
        'nome_evento'
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id_instituicao');
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    }

    public function voluntarios()
    {
        return $this->belongsToMany(Voluntario::class, 'voluntario_has_evento', 'id_evento', 'id_voluntario')
            ->withPivot('habilidade_voluntario')
            ->withTimestamps();
    }

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'evento_has_habilidade', 'id_evento', 'id_habilidade')
            ->withPivot('meta_evento', 'quantidade_voluntario')
            ->withTimestamps();
    }
}
