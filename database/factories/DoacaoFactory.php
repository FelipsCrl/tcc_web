<?php

namespace Database\Factories;

use App\Models\Doacao;
use App\Models\Instituicao;
use App\Models\Voluntario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doacao>
 */
class DoacaoFactory extends Factory
{
    protected $model = Doacao::class;

    public function definition(): array
    {
        return [
            'id_instituicao' => 1, // Associa uma nova instituição
            'observacao_doacao' => $this->faker->sentence,
            'data_hora_limite_doacao' => $this->faker->dateTimeBetween('now', '+1 year'),
            'nome_doacao' => $this->faker->word,
            'coleta_doacao' => $this->faker->boolean,
            'card_doacao' => $this->faker->boolean,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Doacao $doacao) {
            // Associa o voluntário à doação
            $voluntarios = Voluntario::inRandomOrder()->take(rand(5, 7))->get(); // Pegue de 5 a 15 voluntários aleatoriamente

            foreach ($voluntarios as $voluntario) {
                $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
                $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

                $doacao->voluntarios()->attach($voluntario->id_voluntario, [
                    'situacao_solicitacao_doacao' => $this->faker->randomElement([-1, 0, 1]),
                    'data_hora_coleta' => $this->faker->randomElement([Carbon::parse($createdAt)->addDays($this->faker->numberBetween(1, 10)), null]),
                    'categoria_doacao' => $this->faker->sentence(2),
                    'quantidade_doacao' => $this->faker->numberBetween(1, 30),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                if ($doacao->id_doacao == 1) {
                    // Cria associações específicas para a doação com ID 1
                    $voluntarios = Voluntario::inRandomOrder()->take(5)->get(); // Seleciona 5 voluntários aleatórios para a doação 1
                    foreach ($voluntarios as $voluntario) {
                        $doacao->voluntarios()->attach($voluntario->id_voluntario, [
                            'situacao_solicitacao_doacao' => 1, // Apenas voluntários confirmados
                            'data_hora_coleta' => Carbon::parse($createdAt)->addDays($this->faker->numberBetween(1, 5)),
                            'categoria_doacao' => 'Categoria Especial', // Categoria fixa para a doação 1
                            'quantidade_doacao' => $this->faker->numberBetween(10, 20), // Valores maiores
                            'created_at' => $createdAt,
                            'updated_at' => $updatedAt,
                        ]);
                    }
                }
            }
        });
    }
}
