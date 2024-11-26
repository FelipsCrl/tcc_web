<?php

namespace Database\Seeders;

use App\Models\CategoriaDoacao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaDoacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemplo de categoria de doação 1
        CategoriaDoacao::create([
            'descricao_categoria' => 'Alimentos não perecíveis'
        ]);

        // Exemplo de categoria de doação 2
        CategoriaDoacao::create([
            'descricao_categoria' => 'Roupas e acessórios'
        ]);

        // Exemplo de categoria de doação 3
        CategoriaDoacao::create([
            'descricao_categoria' => 'Material escolar'
        ]);

        // Exemplo de categoria de doação 4
        CategoriaDoacao::create([
            'descricao_categoria' => 'Produtos de higiene pessoal'
        ]);

        // Exemplo de categoria de doação 5
        CategoriaDoacao::create([
            'descricao_categoria' => 'Brinquedos'
        ]);

        DB::table('doacao_has_categoria_doacao')->insert([
            [
                'id_doacao' => 1, // Assumindo que o ID 1 existe na tabela doacao
                'id_categoria' => 1, // Assumindo que o ID 1 existe na tabela categoria_doacao
                'meta_doacao_categoria' => 100,
                'quantidade_doacao_categoria' => 50
            ],
            [
                'id_doacao' => 2, // Assumindo que o ID 1 existe na tabela doacao
                'id_categoria' => 2, // Assumindo que o ID 2 existe na tabela categoria_doacao
                'meta_doacao_categoria' => 50,
                'quantidade_doacao_categoria' => 20
            ],
            // Adicione mais entradas conforme necessário
        ]);

        CategoriaDoacao::factory()->count(30)->create();
    }
}
