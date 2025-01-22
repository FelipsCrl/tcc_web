<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemplo de evento 1
        Evento::create([
            'descricao_evento' => 'Workshop de Comunicação',
            'data_hora_evento' => '2025-09-20 10:00:00',
            'id_instituicao' => 1, // Assumindo que o ID 1 existe na tabela instituicao
            'id_endereco' => null, // Assumindo que o endereço é o mesmo que o da instituicao
            'data_hora_limite_evento' => '2025-09-19 23:59:59',
            'nome_evento' => 'Workshop Comunicação'
        ]);

        // Exemplo de evento 2
        Evento::create([
            'descricao_evento' => 'Curso de Culinária',
            'data_hora_evento' => '2025-10-05 14:00:00',
            'id_instituicao' => 1, // Assumindo que o ID 1 existe na tabela instituicao
            'id_endereco' => 3, // Assumindo que o ID 3 existe na tabela endereco
            'data_hora_limite_evento' => '2025-10-04 23:59:59',
            'nome_evento' => 'Curso Culinária'
        ]);

        DB::table('voluntario_has_evento')->insert([
            [
                'id_voluntario' => 1, // Assumindo que o ID 1 existe na tabela voluntario
                'id_evento' => 1, // Assumindo que o ID 1 existe na tabela evento
                'habilidade_voluntario' => 'Enfermagem'
            ],
            [
                'id_voluntario' => 1, // Assumindo que o ID 1 existe na tabela voluntario
                'id_evento' => 2, // Assumindo que o ID 2 existe na tabela evento
                'habilidade_voluntario' => 'Música'
            ],
        ]);

        DB::table('evento_has_habilidade')->insert([
            [
                'id_evento' => 1, // Verifique se o evento de ID 1 existe
                'id_habilidade' => 1, // Verifique se a habilidade de ID 1 existe
                'meta_evento' => 10,
                'quantidade_voluntario' => 5
            ],
            [
                'id_evento' => 2, // Verifique se o evento de ID 2 existe
                'id_habilidade' => 2, // Verifique se a habilidade de ID 2 existe
                'meta_evento' => 3,
                'quantidade_voluntario' => 3
            ],
        ]);

        // Gera 12 eventos (um para cada mês)
        /*foreach (range(1, 12) as $month) {
            Evento::factory()
                ->count(5) // 5 eventos por mês
                ->create([
                    'data_hora_evento' => now()->startOfYear()->addMonths($month - 1)->addDays(rand(1, 28)),
                    'data_hora_limite_evento' => now()->startOfYear()->addMonths($month - 1)->addDays(rand(1, 28)),
                ]);
        }*/
    }
}
