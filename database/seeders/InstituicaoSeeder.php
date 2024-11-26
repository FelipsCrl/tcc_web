<?php

namespace Database\Seeders;

use App\Models\Instituicao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstituicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instituicao::create([
            'id_usuario' => 1, // Assumindo que o ID 1 existe na tabela usuario
            'id_contato' => 1, // Assumindo que o ID 1 existe na tabela contato
            'id_endereco' => 1, // Assumindo que o ID 1 existe na tabela endereco
            'descricao_instituicao' => 'Tem empatia por todos aqueles que necessitam de um cuidado especial',
            'funcionamento_instituicao' => json_encode([
                'Segunda' => ['abertura' => '08:00', 'fechamento' => '17:00', 'funciona' => true],
                'Terça' => ['abertura' => '08:00', 'fechamento' => '17:00', 'funciona' => true],
                'Quarta' => ['abertura' => '08:00', 'fechamento' => '17:00', 'funciona' => true],
                'Quinta' => ['abertura' => '08:00', 'fechamento' => '17:00', 'funciona' => true],
                'Sexta' => ['abertura' => '09:00', 'fechamento' => '18:00', 'funciona' => true],
                'Sábado' => ['abertura' => '09:00', 'fechamento' => '13:00', 'funciona' => false],
                'Domingo' => ['abertura' => '10:00', 'fechamento' => '14:00', 'funciona' => false],
            ]),
            'cnpj_instituicao' => '12.345.678/0001-95'
        ]);


        DB::table('instituicao_has_voluntario')->insert([
            [
                'id_instituicao' => 1, // Assumindo que o ID 1 existe na tabela instituicao
                'id_voluntario' => 1, // Assumindo que o ID 1 existe na tabela voluntario
                'situacao_solicitacao_voluntario' => 0,
                'habilidade_voluntario' => 'Música'
            ],
        ]);

        Instituicao::factory()->count(30)->create();
    }
}
