<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDoacao extends Model
{
    use HasFactory;

    protected $table = 'categoria_doacao';

    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'descricao_categoria'
    ];

    public function doacoes()
    {
        return $this->belongsToMany(Doacao::class, 'doacao_has_categoria_doacao', 'id_categoria', 'id_doacao')
            ->withPivot('meta_doacao_categoria', 'quantidade_doacao_categoria')
            ->withTimestamps();
    }
}
