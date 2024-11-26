<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    use HasFactory;

    protected $table = 'contato';

    protected $primaryKey = 'id_contato';

    protected $fillable = [
        'telefone_contato',
        'whatsapp_contato',
        'facebook_contato',
        'instagram_contato',
        'site_contato'
    ];

    public function instituicoes()
    {
        return $this->hasMany(Instituicao::class, 'id_contato');
    }

    public function voluntarios()
    {
        return $this->hasMany(Voluntario::class, 'id_contato');
    }
}
