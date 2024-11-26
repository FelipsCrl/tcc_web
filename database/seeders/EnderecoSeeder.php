<?php

namespace Database\Seeders;

use App\Models\Endereco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnderecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Endereco::create([
            'cep_endereco' => '12345-678',
            'complemento_endereco' => 'Apto 101',
            'cidade_endereco' => 'São Paulo',
            'logradouro_endereco' => 'Rua das Flores',
            'estado_endereco' => 'SP',
            'bairro_endereco' => 'Jardim Primavera',
            'numero_endereco' => '123'
        ]);

        // Exemplo de endereço 2
        Endereco::create([
            'cep_endereco' => '87654-321',
            'complemento_endereco' => 'Casa',
            'cidade_endereco' => 'Rio de Janeiro',
            'logradouro_endereco' => 'Avenida Atlântica',
            'estado_endereco' => 'RJ',
            'bairro_endereco' => 'Copacabana',
            'numero_endereco' => '456'
        ]);

        // Exemplo de endereço 3
        Endereco::create([
            'cep_endereco' => '35570-154',
            'complemento_endereco' => 'Casa',
            'cidade_endereco' => 'Formiga',
            'logradouro_endereco' => 'Chile',
            'estado_endereco' => 'MG',
            'bairro_endereco' => 'Centro',
            'numero_endereco' => '300'
        ]);

        Endereco::factory()->count(20)->create();
    }
}
