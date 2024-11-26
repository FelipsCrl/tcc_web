<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voluntario extends Model
{
    use HasFactory;

    protected $table = 'voluntario';

    protected $primaryKey = 'id_voluntario';

    protected $fillable = [
        'id_usuario',
        'id_contato',
        'id_endereco',
        'cpf_voluntario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function contato()
    {
        return $this->belongsTo(Contato::class, 'id_contato');
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'voluntario_has_evento', 'id_voluntario', 'id_evento')
            ->withPivot('habilidade_voluntario')
            ->withTimestamps();
    }

    public function instituicoes()
    {
        return $this->belongsToMany(Instituicao::class, 'instituicao_has_voluntario', 'id_instituicao' ,'id_voluntario')
            ->withPivot('habilidade_voluntario')
            ->withTimestamps();
    }

    public function doacoes()
    {
        return $this->belongsToMany(Doacao::class, 'voluntario_has_doacao', 'id_voluntario', 'id_doacao')
            ->withPivot('quantidade_doacao','situacao_solicitacao_doacao', 'data_hora_coleta', 'categoria_doacao')
            ->withTimestamps();
    }

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'voluntario_has_habilidade', 'id_voluntario', 'id_habilidade')
                    ->withTimestamps();
    }
}
