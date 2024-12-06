<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\Habilidade;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabilidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilidades = [
            'Comunicação',
            'Cuidados',
            'Educação',
            'Culinária',
            'Música',
            'Dança',
            'Administração',
            'Animais',
            'Marketing',
            'TI',
            'Pesquisa',
            'Artes Marciais',
            'Enfermagem',
            'Medicina'
        ];

        foreach ($habilidades as $descricao) {
            Habilidade::create([
                'descricao_habilidade' => $descricao,
            ]);
        }
    }
}
