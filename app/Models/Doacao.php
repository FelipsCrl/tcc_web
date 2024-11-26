<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    use HasFactory;

    protected $table = 'doacao';

    protected $primaryKey = 'id_doacao';

    protected $fillable = [
        'id_instituicao',
        'observacao_doacao',
        'data_hora_limite_doacao',
        'nome_doacao',
        'coleta_doacao',
        'card_doacao'
    ];

    protected $attributes = [
        'card_doacao' => 0, // valor padrão
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id_instituicao');
    }

    public function categorias()
    {
        return $this->belongsToMany(CategoriaDoacao::class, 'doacao_has_categoria_doacao', 'id_doacao', 'id_categoria')
            ->withPivot('meta_doacao_categoria', 'quantidade_doacao_categoria')
            ->withTimestamps();
    }

    public function voluntarios()
    {
        return $this->belongsToMany(Voluntario::class, 'voluntario_has_doacao', 'id_doacao', 'id_voluntario')
            ->withPivot('situacao_solicitacao_doacao', 'data_hora_coleta', 'quantidade_doacao', 'categoria_doacao')
            ->withTimestamps();
    }
}
