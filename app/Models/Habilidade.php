<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidade extends Model
{
    use HasFactory;

    protected $table = 'habilidade';

    protected $primaryKey = 'id_habilidade';

    protected $fillable = [
        'descricao_habilidade'
    ];

    public function voluntarios()
    {
        return $this->belongsToMany(Voluntario::class, 'voluntario_has_habilidade', 'id_habilidade', 'id_voluntario')
                    ->withTimestamps();
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_has_habilidade', 'id_evento', 'id_habilidade')
            ->withPivot('meta_evento', 'quantidade_voluntario')
            ->withTimestamps();
    }
}
