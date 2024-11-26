<?php

namespace Database\Seeders;

use App\Models\Voluntario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoluntarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Exemplo de voluntário 1
        Voluntario::create([
            'id_usuario' => 2, // Assumindo que o ID 2 existe na tabela usuario
            'id_contato' => 2, // Assumindo que o ID 2 existe na tabela contato
            'id_endereco' => 2, // Assumindo que o ID 2 existe na tabela endereco
            'cpf_voluntario' => '987.654.321-00'
        ]);

        DB::table('voluntario_has_habilidade')->insert([
            [
                'id_voluntario' => 1, // Assumindo que o ID 1 existe na tabela voluntario
                'id_habilidade' => 1, // Assumindo que o ID 1 existe na tabela habilidade
            ],
        ]);

        Voluntario::factory()->count(30)->create();
    }
}
