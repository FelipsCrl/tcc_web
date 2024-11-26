<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    use HasFactory;

    protected $table = 'instituicao';

    protected $primaryKey = 'id_instituicao';

    protected $fillable = [
        'id_usuario',
        'id_contato',
        'id_endereco',
        'descricao_instituicao',
        'funcionamento_instituicao',
        'cnpj_instituicao'
    ];

    protected $hidden = [
        'senha_instituicao',
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

    public function doacoes()
    {
        return $this->hasMany(Doacao::class, 'id_instituicao');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_instituicao');
    }

    public function voluntarios()
    {
        return $this->belongsToMany(Voluntario::class, 'instituicao_has_voluntario', 'id_instituicao', 'id_voluntario')
            ->withPivot('situacao_solicitacao_voluntario', 'habilidade_voluntario')
            ->withTimestamps();
    }
}
