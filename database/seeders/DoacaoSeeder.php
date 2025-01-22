<?php

namespace Database\Seeders;

use App\Models\Doacao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemplo de doação 1
        Doacao::create([
            'id_instituicao' => 1,
            'observacao_doacao' => null,
            'data_hora_limite_doacao' => null,
            'nome_doacao' => null,
            'coleta_doacao' => '0',
            'card_doacao' => '0'
        ]);

        // Exemplo de doação 2
        Doacao::create([
            'id_instituicao' => 1,
            'observacao_doacao' => 'Doação de roupas e alimentos',
            'data_hora_limite_doacao' => '2025-04-03 23:59:59',
            'nome_doacao' => 'Campanha de Páscoa',
            'coleta_doacao' => '1',
            'card_doacao' => '1'
        ]);

        // Exemplo de doação 3
        Doacao::create([
            'id_instituicao' => 1,
            'observacao_doacao' => 'Doação de livros e materiais escolares',
            'data_hora_limite_doacao' => '2025-02-27 23:59:59',
            'nome_doacao' => 'Volta às Aulas',
            'coleta_doacao' => '0',
            'card_doacao' => '1'
        ]);

        // Obter uma instância de voluntário
        $voluntario = Voluntario::find(1); // Supondo que o ID do voluntário seja 1

        // Obter uma instância de doação
        $doacao = Doacao::find(1); // Supondo que o ID da doação seja 1

        // Associar a doação ao voluntário na tabela voluntario_has_doacao
        $voluntario->doacoes()->attach($doacao->id_doacao, [
            'situacao_solicitacao_doacao' => 1,
            'data_hora_coleta' => null,
            'categoria_doacao' => 'Alimento',
            'quantidade_doacao' => 5
        ]);

        // Obter uma instância de doação
        $doacao = Doacao::find(2); // Supondo que o ID da doação seja 2

        // Associar a doação ao voluntário na tabela voluntario_has_doacao
        $voluntario->doacoes()->attach($doacao->id_doacao, [
            'situacao_solicitacao_doacao' => 1,
            'data_hora_coleta' => Carbon::now(),
            'categoria_doacao' => 'Roupa',
            'quantidade_doacao' => 2
        ]);

        // Obter uma instância de doação
        $doacao = Doacao::find(3); // Supondo que o ID da doação seja 2

        // Associar a doação ao voluntário na tabela voluntario_has_doacao
        $voluntario->doacoes()->attach($doacao->id_doacao, [
            'situacao_solicitacao_doacao' => 1,
            'data_hora_coleta' => null,
            'categoria_doacao' => 'Calçado',
            'quantidade_doacao' => 3
        ]);

        // Gera 12 doações (um para cada mês)
        /*foreach (range(1, 12) as $month) {
            Doacao::factory()
                ->count(5) // 5 doações por mês
                ->create([
                    'data_hora_limite_doacao' => now()->startOfYear()->addMonths($month - 1)->addDays(rand(1, 28)),
                ]);
        }*/
    }
}
